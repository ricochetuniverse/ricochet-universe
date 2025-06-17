import {useCallback, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Row from 'react-bootstrap/Row';
import Loadable from 'react-loadable';

import CustomFileInput from '../CustomFileInput';
import useObjectURL from '../helpers/useObjectURL';
import LoadingComponent from '../LoadingComponent';

import type {ModRequirement} from './check-for-mods';
import checkForMods from './check-for-mods';
import DownloadButton from './DownloadButton';
import type {InflateResult} from './inflate-file';
import {inflateFile} from './inflate-file';

function generateBlobUrl(raw: Uint8Array, type: string): string {
    const blob = new Blob([raw], {type});
    return window.URL.createObjectURL(blob);
}

function processFile(file: File) {
    // should be unknown
    if (file.type !== '' && file.type !== 'application/ms-tnef') {
        throw new Error(
            'File should be .RicochetI, .RicochetLW, .Sequence, .Frame or .dat'
        );
    }

    return new Promise<ProgressEvent>((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = resolve;
        reader.onerror = reject;
        reader.readAsArrayBuffer(file);
    });
}

const LoadableDecompressorEditor = Loadable({
    loader: () =>
        import(
            /* webpackChunkName: "decompressor-editor" */ './DecompressorEditor'
        ),
    loading(props) {
        return (
            <Card.Body>
                <LoadingComponent {...props} text="Loading text viewer..." />
            </Card.Body>
        );
    },
    timeout: 10000,
});

export default function DecompressorApp() {
    const [fileName, setFileName] = useState('');
    const [error, setError] = useState<string | null>(null);

    const [result, setResult] = useState<InflateResult | null>(null);
    const [blobUrls, setBlobUrls] = useState<{
        text: string | null;
        image: string | null;
    }>({text: null, image: null});
    const [modRequirement, setModRequirement] = useState<ModRequirement>({
        result: false,
    });

    const [enableBrowserTextEditor, setEnableBrowserTextEditor] =
        useState(false);

    useObjectURL(blobUrls.text);
    useObjectURL(blobUrls.image);

    const changeViewInBrowserOption = useCallback((ev: Event) => {
        const checkbox = ev.target;
        if (!(checkbox instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }
        const checked = checkbox.checked;

        setEnableBrowserTextEditor(checked);
        if (checked) {
            LoadableDecompressorEditor.preload();
        }
    }, []);

    const onFileReaderFile = useCallback(
        (fileName: string, buffer: ProgressEvent) => {
            const reader = buffer.currentTarget;
            if (
                !(reader instanceof FileReader) ||
                !(reader.result instanceof ArrayBuffer)
            ) {
                throw new Error();
            }

            setFileName(fileName);

            let inflateResult: InflateResult;
            try {
                inflateResult = inflateFile(reader.result);
            } catch (ex) {
                console.error(ex);

                inflateResult = {
                    raw: null,
                    utf8: '',
                    image: null,
                };
            }

            if (!inflateResult.raw && !inflateResult.image) {
                let error =
                    'This file isn’t supported by the decompressor yet.';
                if (fileName.endsWith('.Sequence')) {
                    error =
                        'This file seems to be a Sequence but can’t be decompressed, please report this bug.';
                } else if (fileName.endsWith('.Frame')) {
                    error =
                        'This file seems to be a Frame but can’t be decompressed, please report this bug.';
                }

                throw new Error(error);
            }

            setResult(inflateResult);
            setBlobUrls({
                text: inflateResult.raw
                    ? generateBlobUrl(inflateResult.raw, 'text/plain')
                    : null,
                image: inflateResult.image
                    ? generateBlobUrl(inflateResult.image, 'image/jpeg')
                    : null,
            });
            setModRequirement(checkForMods(inflateResult.utf8));
        },
        []
    );

    const onFileChange = useCallback(
        async (ev: Event) => {
            setFileName('');
            setError(null);

            setResult(null);
            setModRequirement({result: false});

            try {
                const fileInput = ev.currentTarget;
                if (!(fileInput instanceof HTMLInputElement)) {
                    throw new Error('Expected HTMLInputElement');
                }

                const files = fileInput.files;
                if (!files || files.length === 0) {
                    return;
                }

                const buffer = await processFile(files[0]);
                onFileReaderFile(files[0].name, buffer);
            } catch (ex) {
                console.error(ex);

                if (ex instanceof Error) {
                    setError(ex.message);
                } else {
                    setError(
                        ex?.toString() ??
                            'There was a problem decompressing the file.'
                    );
                }
            }
        },
        [onFileReaderFile]
    );

    return (
        <div className="mb-n3">
            <Card className="mb-3">
                <Card.Header>Decompressor</Card.Header>

                <Card.Body>
                    <p>
                        Decompress Ricochet levels (<code>.RicochetI</code>/
                        <code>.RicochetLW</code>
                        ), images (<code>.Sequence</code>/<code>.Frame</code>),
                        your stats (<code>Stats.dat</code>) and level set cache
                        (<code>Levelsets.dat</code>) to view their text/image
                        data.
                    </p>

                    <Form.Group className="mb-3">
                        <Form.Check
                            type="checkbox"
                            id="useBrowserTextEditor"
                            checked={enableBrowserTextEditor}
                            onChange={changeViewInBrowserOption}
                            label="View text in browser"
                        />
                    </Form.Group>

                    <CustomFileInput
                        accept=".RicochetI,.RicochetLW,.Sequence,.Frame,.dat"
                        onChange={onFileChange}
                    />
                </Card.Body>
            </Card>

            {error ? <Alert variant="danger">{error}</Alert> : null}

            {modRequirement.result ? (
                <Alert variant="info">
                    {modRequirement.mods.length >= 2
                        ? `This level set requires these mods to play: ${modRequirement.mods.join(
                              ', '
                          )}`
                        : modRequirement.mods.length === 1
                          ? `This level set requires the ${modRequirement.mods[0]} mod to play.`
                          : 'This level set requires files that are not available on the base game.'}
                </Alert>
            ) : null}

            {result ? (
                <>
                    {blobUrls.image != null ? (
                        <Card className="mb-3">
                            <Card.Header>Decompressed image</Card.Header>

                            <Card.Body>
                                <DownloadButton
                                    blobUrl={blobUrls.image}
                                    fileName={
                                        fileName.replace(/\.Sequence$/, '') +
                                        '.jpg'
                                    }
                                />
                            </Card.Body>

                            <div>
                                <img
                                    src={blobUrls.image}
                                    alt="Decompressed result"
                                    className="decompressor__image"
                                />
                            </div>
                        </Card>
                    ) : null}

                    {result.utf8 ? (
                        <Card className="mb-3">
                            <Card.Header>
                                {blobUrls.image == null
                                    ? 'Decompressed text'
                                    : 'Image metadata'}
                            </Card.Header>

                            <Card.Body>
                                <Row className="align-items-center">
                                    {blobUrls.text != null ? (
                                        <Col xs="auto">
                                            <DownloadButton
                                                blobUrl={blobUrls.text}
                                                fileName={
                                                    fileName.replace(
                                                        /\.Ricochet(I|LW)$/,
                                                        ''
                                                    ) + ' (decompressed).txt'
                                                }
                                            />
                                        </Col>
                                    ) : null}

                                    {!blobUrls.image ? (
                                        <Col>
                                            If you’re manually editing this file
                                            with a text editor, be sure to save
                                            the file with Windows (CRLF) line
                                            endings and Windows-1252 text
                                            encoding to ensure game
                                            compatibility.
                                        </Col>
                                    ) : null}
                                </Row>
                            </Card.Body>

                            {enableBrowserTextEditor ? (
                                <LoadableDecompressorEditor
                                    text={result.utf8}
                                />
                            ) : null}
                        </Card>
                    ) : null}
                </>
            ) : null}
        </div>
    );
}
