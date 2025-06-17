import {useCallback, useEffect, useRef, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import {uppie} from 'uppie';

import CustomFileInput from '../CustomFileInput';

import generateZip, {type FileWithPath} from './generate-zip';

function getAssumedDirectoryPrefix(file: File) {
    const path = file.webkitRelativePath;
    const split = path.split('/');

    return split[0] + '/';
}

export default function RedModPackagerApp() {
    const [folderName, setFolderName] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    const [packageTime, setPackageTime] = useState<Date | null>(null);
    const [downloadButtonUrl, setDownloadButtonUrl] = useState<string | null>(
        null
    );
    const [files, setFiles] = useState<FileWithPath[]>([]);

    const fileInputRef = useRef<HTMLInputElement>(null);

    const onFileChange = useCallback(async function (fileInputEvent: Event) {
        reset();

        try {
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
                const path = file.webkitRelativePath;

                if (
                    path.substring(0, directoryPrefix.length) !==
                    directoryPrefix
                ) {
                    throw new Error(
                        'Unexpected directory prefix, should be ' +
                            directoryPrefix
                    );
                }
            }

            setFolderName(
                directoryPrefix.substring(0, directoryPrefix.length - 1)
            );

            const result = await generateZip(
                Array.from(files),
                directoryPrefix
            );

            setPackageTime(new Date());
            setDownloadButtonUrl(window.URL.createObjectURL(result.zip));
            setFiles(result.files);
        } catch (ex) {
            console.error(ex);

            if (ex instanceof Error) {
                setError(ex.message);
            } else {
                setError(
                    ex?.toString() ??
                        'There was a problem packaging these files.'
                );
            }
        }
    }, []);

    function reset() {
        setFolderName(null);
        setError(null);

        setPackageTime(null);
        setDownloadButtonUrl(null);
        setFiles([]);
    }

    function resetButtonClicked() {
        reset();

        const ref = fileInputRef.current;
        if (ref) {
            ref.value = '';
        }
    }

    useEffect(() => {
        const ref = fileInputRef.current;
        if (ref) {
            uppie(ref, onFileChange);
        }
    }, [onFileChange]);

    const [prevDownloadButtonUrl, setPrevDownloadButtonUrl] =
        useState(downloadButtonUrl);
    if (
        downloadButtonUrl !== prevDownloadButtonUrl &&
        prevDownloadButtonUrl != null
    ) {
        window.URL.revokeObjectURL(prevDownloadButtonUrl);
        setPrevDownloadButtonUrl(downloadButtonUrl);
    }

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
                        The folder name will be automatically used as the mod’s
                        name.
                    </p>

                    <div className="d-flex">
                        <CustomFileInput
                            data-testid="file"
                            directory={true}
                            ref={fileInputRef}
                        />

                        <Button
                            variant="outline-primary"
                            disabled={!downloadButtonUrl}
                            className="ms-2"
                            onClick={resetButtonClicked}
                        >
                            Reset
                        </Button>
                    </div>
                </Card.Body>
            </Card>

            {error ? <Alert variant="danger">{error}</Alert> : null}

            {packageTime && downloadButtonUrl ? (
                <Card className="mb-3">
                    <Card.Header>Package ready</Card.Header>

                    <Card.Body>
                        <p>
                            Packaged the <code>{folderName}</code> folder on{' '}
                            {packageTime.toString()}, reset this tool to
                            re-package any new or modified files.
                        </p>

                        <p>
                            Be sure to test your <code>.RED</code> file on a
                            clean copy of the game, there might be differences
                            compared to using the <code>Data</code> folder.
                        </p>

                        <Button
                            as="a"
                            href={downloadButtonUrl}
                            download={folderName + '.red'}
                            variant="outline-primary"
                        >
                            Download {folderName + '.red'}
                        </Button>
                    </Card.Body>
                </Card>
            ) : null}

            {files.length > 0 ? (
                <Card className="mb-3">
                    <Card.Header>Packaged files</Card.Header>

                    <Card.Body>
                        <ol class="m-0">
                            {files.map((file) => {
                                return <li key={file.path}>{file.path}</li>;
                            })}
                        </ol>
                    </Card.Body>
                </Card>
            ) : null}
        </div>
    );
}
