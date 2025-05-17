import {Component} from 'preact';
import Alert from 'react-bootstrap/Alert';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Row from 'react-bootstrap/Row';
import Loadable from 'react-loadable';

import CustomFileInput from '../CustomFileInput';
import LoadingComponent from '../LoadingComponent';

import type {ModRequirement} from './check-for-mods';
import checkForMods from './check-for-mods';
import type {InflateResult} from './inflate-file';
import {inflateFile} from './inflate-file';

type State = Readonly<{
    fileName: string;
    error: string;

    result: InflateResult | null | undefined;
    blobUrls: {
        text: string;
        image: string;
    };
    modRequirement: ModRequirement;

    useBrowserTextEditor: boolean;
}>;

function generateBlobUrl(raw: Uint8Array, type: string): string {
    const blob = new Blob([raw], {type});
    return window.URL.createObjectURL(blob);
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

function DownloadButton(
    props: Readonly<{
        blobUrl: string;
        fileName: string;
    }>
) {
    return (
        <Button
            as="a"
            href={props.blobUrl}
            download={props.fileName}
            variant="outline-primary"
        >
            Download
        </Button>
    );
}

export default class DecompressorApp extends Component<{}, State> {
    state: State = {
        fileName: '',
        error: '',

        result: null,
        blobUrls: {
            text: '',
            image: '',
        },
        modRequirement: {
            result: false,
        },

        useBrowserTextEditor: false,
    };

    render() {
        const result = this.state.result;

        return (
            <div className="mb-n3">
                <Card className="mb-3">
                    <Card.Header>Decompressor</Card.Header>

                    <Card.Body>
                        <p>
                            Decompress Ricochet levels (<code>.RicochetI</code>/
                            <code>.RicochetLW</code>
                            ), images (<code>.Sequence</code>/
                            <code>.Frame</code>), your stats (
                            <code>Stats.dat</code>) and level set cache (
                            <code>Levelsets.dat</code>) to view their text/image
                            data.
                        </p>

                        <Form.Group className="mb-3">
                            <Form.Check
                                type="checkbox"
                                id="useBrowserTextEditor"
                                checked={this.state.useBrowserTextEditor}
                                onChange={this.onViewInBrowserOptionChange}
                                label="View text in browser"
                            />
                        </Form.Group>

                        <CustomFileInput
                            accept=".RicochetI,.RicochetLW,.Sequence,.Frame,.dat"
                            onChange={this.onFileChange}
                        />
                    </Card.Body>
                </Card>

                {this.state.error ? (
                    <Alert variant="danger">{this.state.error}</Alert>
                ) : null}

                {this.state.modRequirement.result ? (
                    <Alert variant="info">
                        {this.state.modRequirement.mods.length >= 2
                            ? `This level set requires these mods to play: ${this.state.modRequirement.mods.join(
                                  ', '
                              )}`
                            : this.state.modRequirement.mods.length === 1
                              ? `This level set requires the ${this.state.modRequirement.mods[0]} mod to play.`
                              : 'This level set requires files that are not available on the base game.'}
                    </Alert>
                ) : null}

                {result ? (
                    <>
                        {this.state.blobUrls.image !== '' ? (
                            <Card className="mb-3">
                                <Card.Header>Decompressed image</Card.Header>

                                <Card.Body>
                                    <DownloadButton
                                        blobUrl={this.state.blobUrls.image}
                                        fileName={
                                            this.state.fileName.replace(
                                                /\.Sequence$/,
                                                ''
                                            ) + '.jpg'
                                        }
                                    />
                                </Card.Body>

                                <div>
                                    <img
                                        src={this.state.blobUrls.image}
                                        alt="Decompressed result"
                                        className="decompressor__image"
                                    />
                                </div>
                            </Card>
                        ) : null}

                        {result.utf8 ? (
                            <Card className="mb-3">
                                <Card.Header>
                                    {this.state.blobUrls.image === ''
                                        ? 'Decompressed text'
                                        : 'Image metadata'}
                                </Card.Header>

                                <Card.Body>
                                    <Row className="align-items-center">
                                        {this.state.blobUrls.text !== '' ? (
                                            <Col xs="auto">
                                                <DownloadButton
                                                    blobUrl={
                                                        this.state.blobUrls.text
                                                    }
                                                    fileName={
                                                        this.state.fileName.replace(
                                                            /\.Ricochet(I|LW)$/,
                                                            ''
                                                        ) +
                                                        ' (decompressed).txt'
                                                    }
                                                />
                                            </Col>
                                        ) : null}

                                        {!this.state.blobUrls.image ? (
                                            <Col>
                                                If you’re manually editing this
                                                file with a text editor, be sure
                                                to save the file with Windows
                                                (CRLF) line endings and
                                                Windows-1252 text encoding to
                                                ensure game compatibility.
                                            </Col>
                                        ) : null}
                                    </Row>
                                </Card.Body>

                                {result && this.state.useBrowserTextEditor ? (
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

    componentDidUpdate(_prevProps: {}, prevState: State) {
        if (this.state.blobUrls.text !== prevState.blobUrls.text) {
            window.URL.revokeObjectURL(prevState.blobUrls.text);
        }

        if (this.state.blobUrls.image !== prevState.blobUrls.image) {
            window.URL.revokeObjectURL(prevState.blobUrls.image);
        }
    }

    onViewInBrowserOptionChange = (ev: Event) => {
        const checkbox = ev.target;
        if (!(checkbox instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }
        const checked = checkbox.checked;

        this.setState({useBrowserTextEditor: checked});
        if (checked) {
            LoadableDecompressorEditor.preload();
        }
    };

    onFileChange = (ev: Event) => {
        this.setState({
            fileName: '',
            error: '',

            result: null,
            modRequirement: {
                result: false,
            },
        });

        const fileInput = ev.currentTarget;
        if (!(fileInput instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }
        if (fileInput.files && fileInput.files[0]) {
            this.processFile(fileInput.files[0]);
        }
    };

    processFile = (file: File) => {
        // should be unknown
        if (file.type !== '' && file.type !== 'application/ms-tnef') {
            this.setState({
                error: 'File should be .RicochetI, .RicochetLW, .Sequence, .Frame or .dat',
            });
            return;
        }

        this.setState({fileName: file.name}, () => {
            const reader = new FileReader();
            reader.onload = this.onFileReaderFile;
            reader.onerror = (ex) => {
                this.setState({error: 'There was a problem reading the file.'});
                throw ex;
            };
            reader.readAsArrayBuffer(file);
        });
    };

    onFileReaderFile = (buffer: ProgressEvent) => {
        const reader = buffer.currentTarget;
        if (
            !(reader instanceof FileReader) ||
            !(reader.result instanceof ArrayBuffer)
        ) {
            throw new Error();
        }

        let result: InflateResult;
        try {
            result = inflateFile(reader.result);
        } catch (ex) {
            console.error(ex);

            result = {
                raw: null,
                utf8: '',
                image: null,
            };
        }

        if (!result.raw && !result.image) {
            let error = 'This file isn’t supported by the decompressor yet.';

            const fileName = this.state.fileName;
            if (fileName.endsWith('.Sequence')) {
                error =
                    'This file seems to be a Sequence but can’t be decompressed, please report this bug.';
            } else if (fileName.endsWith('.Frame')) {
                error =
                    'This file seems to be a Frame but can’t be decompressed, please report this bug.';
            }

            this.setState({error});
            return;
        }

        this.setState({
            result,
            blobUrls: {
                text: result.raw
                    ? generateBlobUrl(result.raw, 'text/plain')
                    : '',
                image: result.image
                    ? generateBlobUrl(result.image, 'image/jpeg')
                    : '',
            },
            modRequirement: checkForMods(result.utf8),
        });
    };
}
