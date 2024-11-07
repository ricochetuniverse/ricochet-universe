// @flow

import {Component, createRef} from 'preact';
import {Alert, Button, Card, CardBody, CardHeader} from 'reactstrap';
import {uppie} from 'uppie';

import CustomFileInput from '../CustomFileInput';

import generateZip from './generate-zip';

type State = $ReadOnly<{|
    folderName: string,
    error: string,

    packageTime: ?Date,
    downloadButtonUrl: string,
|}>;

function getAssumedDirectoryPrefix(file: File) {
    const path = getFileRelativePath(file);
    const split = path.split('/');

    return split[0] + '/';
}

function getFileRelativePath(file: File): string {
    // $FlowFixMe[prop-missing] https://developer.mozilla.org/en-US/docs/Web/API/File/webkitRelativePath
    return (file.webkitRelativePath: string);
}

export default class RedModPackagerApp extends Component<{||}, State> {
    state: State = {
        folderName: '',
        error: '',

        packageTime: null,
        downloadButtonUrl: '',
    };

    fileInputRef: {|
        current: null | HTMLInputElement,
    |} = createRef<HTMLInputElement>();

    render(): React.Node {
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

                {this.state.packageTime && this.state.downloadButtonUrl ? (
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
        uppie(this.fileInputRef.current, this.onFileChange);
    }

    componentDidUpdate(prevProps: {||}, prevState: State) {
        if (this.state.downloadButtonUrl !== prevState.downloadButtonUrl) {
            window.URL.revokeObjectURL(prevState.downloadButtonUrl);
        }
    }

    onFileChange: (fileInputEvent: Event) => void = (
        fileInputEvent: Event /*, formData, files*/
    ) => {
        this.reset(() => {
            const fileInput = fileInputEvent.target;
            if (!(fileInput instanceof HTMLInputElement)) {
                throw new Error('Expected HTMLInputElement');
            }
            const files = fileInput.files;

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

    reset: (callback?: () => mixed) => void = (
        callback: () => mixed = () => {}
    ) => {
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

    resetButtonClicked: () => void = () => {
        this.reset();

        const ref = this.fileInputRef.current;
        if (ref) {
            ref.value = '';
        }
    };
}
