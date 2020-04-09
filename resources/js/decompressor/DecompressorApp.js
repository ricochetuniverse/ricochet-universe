// @flow

import type {ModRequirement} from './check-for-mods';
import type {InflateResult} from './inflate-file';

import {Component, Fragment, h} from 'preact';
import Loadable from 'react-loadable';
import {
    Alert,
    Button,
    Card,
    CardBody,
    CardHeader,
    CustomInput,
    FormGroup,
    Row,
    Col,
} from 'reactstrap';

import CustomFileInput from '../CustomFileInput';
import IncompatibleBrowser from '../IncompatibleBrowser';
import LoadingComponent from '../LoadingComponent';

import checkForMods from './check-for-mods';
import {inflateFile} from './inflate-file';

type State = $ReadOnly<{|
    fileName: string,
    error: string,

    result: ?InflateResult,
    blobUrls: {|
        text: string,
        image: string,
    |},
    modRequirement: ModRequirement,

    useBrowserTextEditor: boolean,
|}>;

function isBrowserCompatible() {
    return (
        typeof FileReader !== 'undefined' &&
        typeof Blob !== 'undefined' &&
        typeof TextDecoder !== 'undefined'
    );
}

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
            <CardBody>
                <LoadingComponent {...props} text="Loading text viewer..." />
            </CardBody>
        );
    },
    timeout: 10000,
});

function DownloadButton(
    props: $ReadOnly<{|
        blobUrl: string,
        fileName: string,
    |}>
) {
    return (
        <Button
            tag="a"
            href={props.blobUrl}
            download={props.fileName}
            outline
            color="primary"
        >
            Download
        </Button>
    );
}

export default class DecompressorApp extends Component<{||}, State> {
    state = {
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
        if (!isBrowserCompatible()) {
            return <IncompatibleBrowser />;
        }

        return (
            <div className="mb-n3">
                <Card className="mb-3">
                    <CardHeader>Decompressor</CardHeader>

                    <CardBody>
                        <p>
                            Decompress Ricochet levels (<code>.RicochetI</code>/
                            <code>.RicochetLW</code>
                            ), your stats (<code>Stats.dat</code>) and level set
                            cache (<code>Levelsets.dat</code>) to view their raw
                            text data.
                        </p>

                        <FormGroup>
                            <CustomInput
                                type="checkbox"
                                id="useBrowserTextEditor"
                                checked={this.state.useBrowserTextEditor}
                                label="View text in browser"
                                onChange={this.onViewInBrowserOptionChange}
                            />
                        </FormGroup>

                        <CustomFileInput
                            label={this.state.fileName}
                            accept=".RicochetI,.RicochetLW,.dat,.Sequence"
                            onChange={this.onFileChange}
                        />
                    </CardBody>
                </Card>

                {this.state.error ? (
                    <Alert color="danger" fade={false}>
                        {this.state.error}
                    </Alert>
                ) : null}

                {this.state.modRequirement.result ? (
                    <Alert color="info" fade={false}>
                        {this.state.modRequirement.mod
                            ? `This level set requires the ${this.state.modRequirement.mod} mod to play.`
                            : 'This level set requires files that are not available on the base game.'}
                    </Alert>
                ) : null}

                {this.state.result ? (
                    <>
                        {this.state.blobUrls.image !== '' ? (
                            <Card className="mb-3">
                                <CardHeader>Decompressed image</CardHeader>

                                <CardBody>
                                    <DownloadButton
                                        blobUrl={this.state.blobUrls.image}
                                        fileName={
                                            this.state.fileName.replace(
                                                /\.Sequence$/,
                                                ''
                                            ) + '.jpg'
                                        }
                                    />
                                </CardBody>

                                <div>
                                    <img
                                        src={this.state.blobUrls.image}
                                        alt="Decompressed result"
                                        className="decompressor__image"
                                    />
                                </div>
                            </Card>
                        ) : null}

                        {this.state.result.utf8 ? (
                            <Card className="mb-3">
                                <CardHeader>
                                    {this.state.blobUrls.image === ''
                                        ? 'Decompressed text'
                                        : 'Image metadata'}
                                </CardHeader>

                                <CardBody>
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

                                        <Col>
                                            If you’re editing this file with a
                                            text editor, be sure to save the
                                            file with Windows (CRLF) line
                                            endings and Windows-1252 text
                                            encoding to ensure game
                                            compatibility.
                                        </Col>
                                    </Row>
                                </CardBody>

                                {this.state.result &&
                                this.state.useBrowserTextEditor ? (
                                    <LoadableDecompressorEditor
                                        text={this.state.result.utf8}
                                    />
                                ) : null}
                            </Card>
                        ) : null}
                    </>
                ) : null}
            </div>
        );
    }

    componentDidUpdate(prevProps: {||}, prevState: State) {
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
                error: 'File should be .RicochetI or .RicochetLW or .dat',
            });
            return;
        }

        this.setState({fileName: file.name});

        const reader = new FileReader();
        reader.onload = this.onFileReaderFile;
        reader.onerror = (ex) => {
            this.setState({error: 'Error reading file'});
            throw ex;
        };
        reader.readAsArrayBuffer(file);
    };

    onFileReaderFile = (buffer: ProgressEvent) => {
        const reader = buffer.currentTarget;
        if (
            !(reader instanceof FileReader) ||
            !(reader.result instanceof ArrayBuffer)
        ) {
            throw new Error();
        }

        const result = inflateFile(reader.result);

        this.setState({
            result,
            blobUrls: {
                text: generateBlobUrl(result.raw, 'text/plain'),
                image: result.image
                    ? generateBlobUrl(result.image, 'image/jpeg')
                    : '',
            },
            modRequirement: checkForMods(result.utf8),
        });
    };
}
