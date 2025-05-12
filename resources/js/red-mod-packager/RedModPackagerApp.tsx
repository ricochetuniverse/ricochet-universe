import {Component, createRef} from 'preact';
import Alert from 'react-bootstrap/Alert';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import {uppie} from 'uppie';

import CustomFileInput from '../CustomFileInput';

import generateZip from './generate-zip';

type State = Readonly<{
    folderName: string;
    error: string;

    packageTime: Date | null;
    downloadButtonUrl: string;
}>;

function getAssumedDirectoryPrefix(file: File) {
    const path = getFileRelativePath(file);
    const split = path.split('/');

    return split[0] + '/';
}

// todo inline this
function getFileRelativePath(file: File): string {
    return file.webkitRelativePath;
}

export default class RedModPackagerApp extends Component<{}, State> {
    state: State = {
        folderName: '',
        error: '',

        packageTime: null,
        downloadButtonUrl: '',
    };

    fileInputRef = createRef<HTMLInputElement>();

    render() {
        return (
            <div className="mb-n3">
                <Card className="mb-3">
                    <Card.Header>RED mod packager</Card.Header>

                    <Card.Body>
                        <p>
                            Select your mod’s folder to package it to a{' '}
                            <code>.RED</code> file.
                        </p>

                        <p>
                            The folder name will be automatically used as the
                            mod’s name.
                        </p>

                        <div className="d-flex">
                            <CustomFileInput
                                directory={true}
                                ref={this.fileInputRef}
                            />

                            <Button
                                variant="outline-primary"
                                disabled={!this.state.downloadButtonUrl}
                                className="ms-2"
                                onClick={this.resetButtonClicked}
                            >
                                Reset
                            </Button>
                        </div>
                    </Card.Body>
                </Card>

                {this.state.error ? (
                    <Alert variant="danger">{this.state.error}</Alert>
                ) : null}

                {this.state.packageTime && this.state.downloadButtonUrl ? (
                    <Card className="mb-3">
                        <Card.Header>Package ready</Card.Header>

                        <Card.Body>
                            <p>
                                Packaged the{' '}
                                <code>{this.state.folderName}</code> folder on{' '}
                                {this.state.packageTime.toString()}, reset this
                                tool to re-package any new or modified files.
                            </p>

                            <p>
                                Be sure to test your <code>.RED</code> file on a
                                clean copy of the game, there might be
                                differences compared to using the{' '}
                                <code>Data</code> folder.
                            </p>

                            <Button
                                as="a"
                                href={this.state.downloadButtonUrl}
                                download={this.state.folderName + '.red'}
                                variant="outline-primary"
                            >
                                Download
                            </Button>
                        </Card.Body>
                    </Card>
                ) : null}
            </div>
        );
    }

    componentDidMount() {
        const ref = this.fileInputRef.current;
        if (ref) {
            uppie(ref, this.onFileChange);
        }
    }

    componentDidUpdate(_prevProps: {}, prevState: State) {
        if (this.state.downloadButtonUrl !== prevState.downloadButtonUrl) {
            window.URL.revokeObjectURL(prevState.downloadButtonUrl);
        }
    }

    onFileChange = (fileInputEvent: Event /*, formData, files*/) => {
        this.reset(() => {
            const fileInput = fileInputEvent.target;
            if (!(fileInput instanceof HTMLInputElement)) {
                throw new Error('Expected HTMLInputElement');
            }

            const files = fileInput.files;
            if (!files || files.length === 0) {
                return;
            }

            // Get the first file's directory prefix, and use it for all
            const directoryPrefix = getAssumedDirectoryPrefix(files[0]);

            // Sanity checking...
            for (let i = 0, len = files.length; i < len; i += 1) {
                const file = files[i];
                const path = getFileRelativePath(file);

                if (
                    path.substr(0, directoryPrefix.length) !== directoryPrefix
                ) {
                    throw new Error(
                        'Unexpected directory prefix, should be ' +
                            directoryPrefix
                    );
                }
            }

            this.setState({
                folderName: directoryPrefix.substr(
                    0,
                    directoryPrefix.length - 1
                ),
            });

            generateZip(files, directoryPrefix).then((blob: Blob) => {
                this.setState({
                    packageTime: new Date(),
                    downloadButtonUrl: window.URL.createObjectURL(blob),
                });
            });
        });
    };

    reset = (callback = () => {}) => {
        this.setState(
            {
                folderName: '',
                error: '',

                packageTime: null,
                downloadButtonUrl: '',
            },
            callback
        );
    };

    resetButtonClicked = () => {
        this.reset();

        const ref = this.fileInputRef.current;
        if (ref) {
            ref.value = '';
        }
    };
}
