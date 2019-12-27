import {Component, createRef, h} from 'preact';
import {
    Alert,
    Button,
    Card,
    CardBody,
    CardHeader,
} from 'reactstrap';
import Uppie from 'uppie';

import CustomFileInput from '../CustomFileInput';

import generateZip from './generate-zip';

function getAssumedDirectoryPrefix(file) {
    const path = file.webkitRelativePath;
    const split = path.split('/');

    return split[0] + '/';
}

export default class RedModPackagerApp extends Component {
    state = {
        folderName: '',
        error: '',

        packageTime: null,
        downloadButtonUrl: '',
    };

    fileInputRef = createRef();

    render() {
        return (
            <div className="mb-n3">
                <Card className="mb-3">
                    <CardHeader>RED mod packager</CardHeader>

                    <CardBody>
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
                                label={this.state.folderName}
                                directory
                                ref={this.fileInputRef}
                            />

                            <Button
                                outline
                                color="primary"
                                disabled={!this.state.downloadButtonUrl}
                                className="flex-shrink-0 ml-3"
                                onClick={this.resetButtonClicked}
                            >
                                Reset
                            </Button>
                        </div>
                    </CardBody>
                </Card>

                {this.state.error ? (
                    <Alert color="danger" fade={false}>
                        {this.state.error}
                    </Alert>
                ) : null}

                {this.state.downloadButtonUrl ? (
                    <Card className="mb-3">
                        <CardHeader>Package ready</CardHeader>

                        <CardBody>
                            <p>
                                Packaged on {this.state.packageTime.toString()},
                                reset this tool to re-package any new or
                                modified files.
                            </p>

                            <p>
                                Be sure to test your <code>.RED</code> file on a
                                clean copy of the game, there might be
                                differences compared to using the{' '}
                                <code>Data</code> folder.
                            </p>

                            <Button
                                tag="a"
                                href={this.state.downloadButtonUrl}
                                download={this.state.folderName + '.red'}
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

    componentDidMount() {
        const uppie = new Uppie();

        uppie(this.fileInputRef.current, this.onFileChange);
    }

    componentDidUpdate(prevProps, prevState) {
        if (this.state.downloadButtonUrl !== prevState.downloadButtonUrl) {
            window.URL.revokeObjectURL(prevState.downloadButtonUrl);
        }
    }

    onFileChange = (fileInputEvent /*, formData, files*/) => {
        this.reset(() => {
            const files = fileInputEvent.target.files;

            // Get the first file's directory prefix, and use it for all
            const directoryPrefix = getAssumedDirectoryPrefix(files[0]);

            // Sanity checking...
            for (let i = 0, len = files.length; i < len; i += 1) {
                const file = files[i];
                const path = file.webkitRelativePath;

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

            generateZip(files, directoryPrefix).then((link) => {
                this.setState({
                    packageTime: new Date(),
                    downloadButtonUrl: link,
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

        this.fileInputRef.current.value = '';
    };
}
