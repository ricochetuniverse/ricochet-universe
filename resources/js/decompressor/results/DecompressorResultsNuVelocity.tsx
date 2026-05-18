import {useEffect, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';

import type {DecompressorResultNuVelocity} from '../DecompressorTypes';
import {unpack} from '../nuvelocity/worker-handler';
import type {WorkerStatuses} from '../nuvelocity/WorkerMessageTypes';

import DecompressorResultsNuVelocityDecoded from './DecompressorResultsNuVelocityDecoded';

type Props = Readonly<{
    dotnetLoaderUrl: string;
    result: DecompressorResultNuVelocity;
}>;

// https://github.com/ricochetuniverse/nuvelocity-unpacker-web
export default function DecompressorResultsNuVelocity(props: Props) {
    const [status, setStatus] = useState<WorkerStatuses | null>(null);
    const [decodedImages, setDecodedImages] = useState<string[]>([]);
    const [errorDetails, setErrorDetails] = useState<Error | null>(null);

    const [prevBytes, setPrevBytes] = useState<Uint8Array | null>(
        props.result.bytes
    );
    if (props.result.bytes !== prevBytes) {
        setPrevBytes(props.result.bytes);

        setStatus(null);
        setDecodedImages([]);
        setErrorDetails(null);
    }

    useEffect(() => {
        let ignore = false;

        unpack(props.dotnetLoaderUrl, props.result.bytes, (response) => {
            if (ignore) {
                return;
            }

            setStatus(response.status);

            switch (response.status) {
                case 'LOADING':
                case 'PROCESSING':
                    break;

                case 'FINISHED': {
                    const decodedImages = JSON.parse(
                        response.decodedImagesJson
                    ) as string[];
                    setDecodedImages(decodedImages);
                    break;
                }

                case 'ERROR':
                    console.error(response.errorDetails);
                    setErrorDetails(new Error(response.errorDetails));
                    break;

                default:
                    break;
            }
        });

        return () => {
            ignore = true;
        };
    }, [props.dotnetLoaderUrl, props.result.bytes]);

    return (
        <Card>
            <Card.Header as="h2">Decompressed sequence image</Card.Header>

            {status === 'LOADING' ? (
                <Card.Body>Loading...</Card.Body>
            ) : status === 'ERROR' ? (
                <Card.Body>
                    <Alert variant="danger">
                        Oops, there was a problem decoding the file
                        {errorDetails ? (
                            <>
                                :
                                <pre className="m-0 mt-3">
                                    <code>{errorDetails.message}</code>
                                </pre>
                            </>
                        ) : null}
                    </Alert>
                </Card.Body>
            ) : status === 'PROCESSING' ? (
                <Card.Body>Decoding images...</Card.Body>
            ) : null}

            {decodedImages.length ? (
                <Card.Body>
                    <DecompressorResultsNuVelocityDecoded
                        decodedImages={decodedImages}
                    />
                </Card.Body>
            ) : null}
        </Card>
    );
}
