import {inflate} from 'pako/lib/inflate';
// noinspection ES6UnusedImports
import {Component, h} from 'preact';
import Loadable from 'react-loadable';
import {
    Alert,
    Button,
    Card,
    CardBody,
    CardHeader,
    CustomInput,
    FormGroup,
} from 'reactstrap';

import IncompatibleBrowser from '../IncompatibleBrowser';
import LoadingComponent from '../LoadingComponent';

import checkForMods from './check-for-mods';
import triggerDownload from '../util/trigger-download';

function isBrowserCompatible() {
    return (
        typeof FileReader !== 'undefined' &&
        typeof Blob !== 'undefined' &&
        typeof TextDecoder !== 'undefined'
    );
}

function getDownloadFileName(fileName) {
    return fileName.replace(/\.Ricochet(I|LW)$/, '') + ' (decompressed).txt';
}

function decodeInflatedResult(inflatedResult) {
    return new TextDecoder('windows-1252', {
        fatal: true,
    }).decode(inflatedResult);
}

const LoadableDecompressorEditor = Loadable({
    loader: () => import('./DecompressorEditor'),
    loading(props) {
        return (
            <CardBody>
                <LoadingComponent {...props} text="Loading text viewer..." />
            </CardBody>
        );
    },
    timeout: 10000,
});

export default class DecompressorApp extends Component {
    state = {
        fileName: '',
        error: '',

        inflatedResult: '',
        utf8Result: '',
        objectUrl: '',

        requiresModResult: false,
        requiresModName: '',

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
                                type="radio"
                                name="output"
                                id="output_disk"
                                checked={!this.state.useBrowserTextEditor}
                                label="Save decompressed result to disk"
                                onChange={this.onOutputRadioButtonChanged.bind(
                                    this,
                                    false
                                )}
                            />

                            <CustomInput
                                type="radio"
                                name="output"
                                id="output_browser"
                                checked={this.state.useBrowserTextEditor}
                                label="View in browser (powered by Visual Studio Code)"
                                onChange={this.onOutputRadioButtonChanged.bind(
                                    this,
                                    true
                                )}
                            />
                        </FormGroup>

                        <CustomInput
                            type="file"
                            label={this.state.fileName}
                            accept=".RicochetI,.RicochetLW,.dat"
                            onChange={this.onFileChange}
                        />
                    </CardBody>
                </Card>

                {this.state.error ? (
                    <Alert color="danger" fade={false}>
                        {this.state.error}
                    </Alert>
                ) : null}

                {this.state.requiresModResult ? (
                    <Alert color="info" fade={false}>
                        {this.state.requiresModName
                            ? 'This level set requires the ' +
                              this.state.requiresModName +
                              ' mod to play.'
                            : 'This level set requires files that are not available on the base game.'}
                    </Alert>
                ) : null}

                {this.state.useBrowserTextEditor && this.state.utf8Result ? (
                    <Card className="mb-3">
                        <CardHeader>Decompressed result</CardHeader>

                        <LoadableDecompressorEditor
                            text={this.state.utf8Result}
                        />
                    </Card>
                ) : null}

                {!this.state.useBrowserTextEditor && this.state.objectUrl ? (
                    <Card className="mb-3">
                        <CardHeader>Decompressed result</CardHeader>

                        <CardBody>
                            <p>
                                You can edit the file with your favorite text
                                editor for advanced scripting, be sure to save
                                the file with Windows (CRLF) line endings and
                                Windows-1252 text encoding.
                            </p>

                            <Button
                                tag="a"
                                href={this.state.objectUrl}
                                download={getDownloadFileName(
                                    this.state.fileName
                                )}
                                outline
                                color="primary"
                            >
                                Download
                            </Button>
                        </CardBody>
                    </Card>
                ) : null}
            </div>
        );
    }

    componentDidUpdate(prevProps, prevState) {
        if (this.state.objectUrl !== prevState.objectUrl) {
            window.URL.revokeObjectURL(prevState.objectUrl);
        }

        if (
            this.state.useBrowserTextEditor !== prevState.useBrowserTextEditor
        ) {
            if (this.state.inflatedResult) {
                if (!this.state.useBrowserTextEditor) {
                    if (!this.state.objectUrl) {
                        this.generateDownload();
                    }
                }
            }
        }
    }

    onOutputRadioButtonChanged(useBrowserTextEditor) {
        this.setState({useBrowserTextEditor});

        if (useBrowserTextEditor) {
            LoadableDecompressorEditor.preload();
        }
    }

    onFileChange = ({currentTarget}) => {
        this.setState({
            fileName: '',
            error: '',

            inflatedResult: '',
            utf8Result: '',
            objectUrl: '',

            requiresModResult: false,
            requiresModName: '',
        });

        if (currentTarget.files && currentTarget.files[0]) {
            this.processFile(currentTarget.files[0]);
        }
    };

    processFile = (file) => {
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
            this.setState({error: ex.message});
            throw ex;
        };
        reader.readAsArrayBuffer(file);
    };

    onFileReaderFile = (buffer) => {
        const compressed = new Uint8Array(buffer.currentTarget.result, 9);
        const inflatedResult = inflate(compressed);
        const utf8Result = decodeInflatedResult(inflatedResult);
        const requiresMod = checkForMods(utf8Result);

        this.setState(
            {
                inflatedResult,
                utf8Result,

                requiresModResult: requiresMod.result,
                requiresModName: requiresMod.mod,
            },
            () => {
                if (!this.state.useBrowserTextEditor) {
                    this.generateDownload().then(this.downloadResult);
                }
            }
        );
    };

    generateDownload() {
        return new Promise((resolve) => {
            const blob = new Blob([this.state.inflatedResult], {
                type: 'text/plain',
            });

            this.setState(
                {objectUrl: window.URL.createObjectURL(blob)},
                resolve
            );
        });
    }

    downloadResult = () => {
        triggerDownload(
            this.state.objectUrl,
            getDownloadFileName(this.state.fileName)
        );
    };
}
