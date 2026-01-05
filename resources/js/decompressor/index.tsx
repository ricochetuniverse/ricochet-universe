import {type ComponentProps, render} from 'preact';
import {lazy} from 'preact/compat';
import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';

import SuspenseComponent from '../SuspenseComponent';

let DecompressorApp = reload();

function reload() {
    return lazy(() => import('./DecompressorApp'));
}

function LoadableDecompressorApp(
    props: ComponentProps<typeof DecompressorApp>
) {
    const [retryTime, setRetryTime] = useState(0);

    return (
        <SuspenseComponent
            fallback={(status) => {
                return (
                    <Card className="mb-3">
                        <Card.Header>Decompressor</Card.Header>

                        <Card.Body>{status}</Card.Body>
                    </Card>
                );
            }}
            retry={() => {
                setRetryTime(Date.now());
                DecompressorApp = reload();
            }}
        >
            <DecompressorApp key={retryTime} {...props} />
        </SuspenseComponent>
    );
}

const root = document.getElementById('decompressor-root');

if (root) {
    render(
        <LoadableDecompressorApp
            dotnetLoaderUrl={root.dataset['dotnetLoaderUrl'] ?? ''}
        />,
        root
    );
}
