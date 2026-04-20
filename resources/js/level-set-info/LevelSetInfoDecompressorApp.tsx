import {useEffect, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';

import {inflateFile} from '../decompressor/inflate-file';
import LoadableDecompressorTextEditor from '../decompressor/LoadableDecompressorTextEditor';

type Props = Readonly<{
    downloadUrl: string;
}>;

export default function LevelSetInfoDecompressorApp(props: Props) {
    const [error, setError] = useState<string | null>(null);

    const [result, setResult] = useState<string | null>(null);

    useEffect(() => {
        const abortController = new AbortController();

        async function downloadFile() {
            setError(null);
            setResult(null);

            try {
                const response = await fetch(props.downloadUrl, {
                    signal: abortController.signal,
                });
                if (!response.ok) {
                    throw new Error(
                        `Failed to download level set (response status: ${response.status})`
                    );
                }

                const inflateResult = inflateFile(await response.arrayBuffer());
                setResult(inflateResult.utf8);
            } catch (ex) {
                console.error(ex);

                if (ex instanceof Error) {
                    setError(ex.message);
                } else {
                    setError(
                        ex?.toString() ??
                            'There was a problem decompressing the level set.'
                    );
                }
            }
        }

        downloadFile();

        return () => {
            abortController.abort();
        };
    }, [props.downloadUrl]);

    return (
        <Card>
            <Card.Header as="h2">Decompressor</Card.Header>

            {error ? (
                <Card.Body>
                    <Alert className="m-0" variant="danger">
                        {error}
                    </Alert>
                </Card.Body>
            ) : result == null ? (
                <Card.Body>Downloading file...</Card.Body>
            ) : (
                <LoadableDecompressorTextEditor text={result} />
            )}
        </Card>
    );
}
