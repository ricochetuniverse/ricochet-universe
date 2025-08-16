import {useCallback, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';

import CustomFileInput from '../CustomFileInput';
import useObjectURL from '../helpers/useObjectURL';

import type {
    DecompressorBlobUrls,
    DecompressorResult,
} from './DecompressorTypes';
import type {InflateResult} from './inflate-file';
import {inflateFile} from './inflate-file';
import LoadableDecompressorTextEditor from './LoadableDecompressorTextEditor';
import DecompressorResultsJs from './results/DecompressorResultsJs';
import DecompressorResultsNuVelocity from './results/DecompressorResultsNuVelocity';

function generateBlobUrl(raw: Uint8Array<ArrayBuffer>, type: string): string {
    const blob = new Blob([raw], {type});
    return window.URL.createObjectURL(blob);
}

function readFileReaderBuffer(fileName: string, buffer: ArrayBuffer) {
    let inflateResult: InflateResult;
    try {
        inflateResult = inflateFile(buffer);
    } catch (ex) {
        console.error(ex);

        inflateResult = {
            raw: null,
            utf8: '',
            image: null,
        };
    }

    if (!inflateResult.raw && !inflateResult.image) {
        let error = 'This file isn’t supported by the decompressor yet.';
        if (fileName.endsWith('.Sequence')) {
            error =
                'This file seems to be a Sequence but can’t be decompressed, please report this bug.';
        } else if (fileName.endsWith('.Frame')) {
            error =
                'This file seems to be a Frame but can’t be decompressed, please report this bug.';
        }

        throw new Error(error);
    }

    return inflateResult;
}

type Props = Readonly<{
    dotnetLoaderUrl: string;
}>;

export default function DecompressorApp(props: Props) {
    const [error, setError] = useState<string | null>(null);

    const [result, setResult] = useState<DecompressorResult | null>(null);
    const [fileName, setFileName] = useState('');
    const [blobUrls, setBlobUrls] = useState<DecompressorBlobUrls>({
        text: null,
        image: null,
    });

    const [enableBrowserTextEditor, setEnableBrowserTextEditor] =
        useState(false);

    useObjectURL(blobUrls.text);
    useObjectURL(blobUrls.image);

    const enableNewImageUnpacker = props.dotnetLoaderUrl !== '';

    const changeViewInBrowserOption = useCallback((ev: Event) => {
        const checkbox = ev.target;
        if (!(checkbox instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }
        const checked = checkbox.checked;

        setEnableBrowserTextEditor(checked);
        if (checked) {
            LoadableDecompressorTextEditor.preload();
        }
    }, []);

    const onFileChange = useCallback(
        async (ev: Event) => {
            setError(null);

            setResult(null);
            setFileName('');
            setBlobUrls({text: null, image: null});

            try {
                const fileInput = ev.currentTarget;
                if (!(fileInput instanceof HTMLInputElement)) {
                    throw new Error('Expected HTMLInputElement');
                }

                const files = fileInput.files;
                if (!files || files.length === 0) {
                    return;
                }
                const file = files[0];

                // should be unknown
                if (file.type !== '' && file.type !== 'application/ms-tnef') {
                    throw new Error(
                        'File should be .RicochetI, .RicochetLW, .Sequence, .Frame or .dat'
                    );
                }

                const buffer = await file.arrayBuffer();

                setFileName(file.name);
                if (file.name.endsWith('.Sequence') && enableNewImageUnpacker) {
                    // Use new unpacker
                    setResult({
                        unpacker: 'NUVELOCITY',
                        bytes: new Uint8Array(buffer),
                    });
                    return;
                }

                const inflateResult = readFileReaderBuffer(file.name, buffer);

                setResult({
                    unpacker: 'JS',
                    text: inflateResult.utf8,
                    image: inflateResult.image ?? undefined,
                });

                setBlobUrls({
                    text: inflateResult.raw
                        ? generateBlobUrl(inflateResult.raw, 'text/plain')
                        : null,
                    image: inflateResult.image
                        ? generateBlobUrl(inflateResult.image, 'image/jpeg')
                        : null,
                });
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
        [enableNewImageUnpacker]
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
                            id="decompressor-enableBrowserTextEditor"
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

            {result?.unpacker === 'JS' ? (
                <DecompressorResultsJs
                    blobUrls={blobUrls}
                    enableBrowserTextEditor={enableBrowserTextEditor}
                    fileName={fileName}
                    result={result}
                />
            ) : null}

            {result?.unpacker === 'NUVELOCITY' ? (
                <DecompressorResultsNuVelocity
                    dotnetLoaderUrl={props.dotnetLoaderUrl}
                    result={result}
                />
            ) : null}
        </div>
    );
}
