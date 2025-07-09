import {useEffect, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';

import useErrorDetails from '../../helpers/useErrorDetails';
import type {DecompressorResultNuVelocity} from '../DecompressorTypes';
import useDotNet from '../useDotNet';

import DecompressorResultsNuVelocityDecoded from './DecompressorResultsNuVelocityDecoded';

type Props = Readonly<{
    dotnetLoaderUrl: string;
    result: DecompressorResultNuVelocity;
}>;

// https://github.com/ricochetuniverse/nuvelocity-unpacker-web
export default function DecompressorResultsNuVelocity(props: Props) {
    const {getDotNet, loading: isDotNetLoading} = useDotNet(
        props.dotnetLoaderUrl
    );

    const [decodedImages, setDecodedImages] = useState<string[]>([]);
    const [isUnpacking, setIsUnpacking] = useState(false);
    const [errorDetails, setErrorDetails] = useErrorDetails();

    const [prevBytes, setPrevBytes] = useState<Uint8Array | null>(
        props.result.bytes
    );
    if (props.result.bytes !== prevBytes) {
        setPrevBytes(props.result.bytes);

        setDecodedImages([]);
        setIsUnpacking(false);
        setErrorDetails({isError: false});
    }

    useEffect(() => {
        let ignore = false;
        let timeout: ReturnType<typeof setTimeout>;

        async function startLoad() {
            try {
                const dotnet = (await getDotNet()) as {
                    Unpacker: {
                        ReadFile(file: Uint8Array): string;
                    };
                };
                if (dotnet == null || ignore) {
                    return;
                }

                setIsUnpacking(true);

                await new Promise((resolve) => {
                    timeout = setTimeout(resolve, 10);
                });
                if (ignore) {
                    return;
                }

                // todo should be done in a web worker because it hangs the main thread
                // use setTimeout hack to hopefully render the loading message
                const decodedImages = JSON.parse(
                    dotnet.Unpacker.ReadFile(props.result.bytes)
                ) as string[];
                if (ignore) {
                    return;
                }

                setDecodedImages(decodedImages);
            } catch (ex) {
                console.error(ex);

                setErrorDetails({
                    isError: true,
                    details: ex instanceof Error ? ex : null,
                });
            } finally {
                setIsUnpacking(false);
            }
        }
        void startLoad();

        return () => {
            ignore = true;
            if (timeout) {
                clearTimeout(timeout);
            }
        };
    }, [getDotNet, props.result.bytes, setErrorDetails]);

    return (
        <Card className="mb-3">
            <Card.Header>Decompressed sequence image</Card.Header>

            {isDotNetLoading ? (
                <Card.Body>Loading...</Card.Body>
            ) : errorDetails.isError ? (
                <Card.Body>
                    <Alert variant="danger">
                        Oops, there was a problem decoding the file:
                        <pre className="m-0 mt-3">
                            <code>{errorDetails.details?.toString()}</code>
                        </pre>
                    </Alert>
                </Card.Body>
            ) : isUnpacking ? (
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
