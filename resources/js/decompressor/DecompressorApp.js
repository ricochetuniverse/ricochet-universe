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

import LoadingComponent from '../LoadingComponent';

function getDownloadFileName(fileName) {
    return fileName.replace(/\.Ricochet(I|LW)$/, '') + ' (decompressed).txt';
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

        useBrowserTextEditor: false,
    };

    render() {
        return (
            <div>
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
                    <Alert color="danger">{this.state.error}</Alert>
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
                if (this.state.useBrowserTextEditor) {
                    if (!this.state.utf8Result) {
                        this.decodeDeflatedResult();
                    }
                } else {
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

        this.setState({inflatedResult}, () => {
            if (!this.state.useBrowserTextEditor) {
                this.generateDownload().then(this.downloadResult);
            } else {
                this.decodeDeflatedResult();
            }
        });
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

    decodeDeflatedResult() {
        const utf8Result = new TextDecoder('windows-1252', {
            fatal: true,
        }).decode(this.state.inflatedResult);

        this.setState({utf8Result});
    }

    downloadResult = () => {
        // Firefox requires link to be inserted in <body> before clicking
        // https://stackoverflow.com/a/27116581
        const link = document.createElement('a');
        link.setAttribute('href', this.state.objectUrl);
        link.setAttribute('download', getDownloadFileName(this.state.fileName));
        link.style.position = 'absolute';
        link.style.top = '0';
        link.style.left = '-10px';
        link.style.visibility = 'hidden';
        link.setAttribute('aria-hidden', 'true');
        link.tabIndex = -1;
        document.body.appendChild(link);
        link.click();
        link.remove();
    };
}
