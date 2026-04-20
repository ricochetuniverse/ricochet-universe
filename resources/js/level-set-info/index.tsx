import {type ComponentProps, render} from 'preact';
import {lazy} from 'preact/compat';
import {useState} from 'preact/hooks';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';

import SuspenseComponent from '../SuspenseComponent';

let LevelSetInfoDecompressorApp = reload();

function reload() {
    return lazy(() => import('./LevelSetInfoDecompressorApp'));
}

function LoadableLevelSetInfoDecompressorApp(
    props: ComponentProps<typeof LevelSetInfoDecompressorApp>
) {
    const [started, setStarted] = useState(false);
    const [retryTime, setRetryTime] = useState(0);

    if (!started) {
        return (
            <Button
                onClick={() => {
                    setStarted(true);
                }}
                variant="outline-primary"
            >
                View raw level contents (advanced)
            </Button>
        );
    }

    return (
        <SuspenseComponent
            fallback={(status) => {
                return (
                    <Card className="mb-3">
                        <Card.Header as="h1">Decompressor</Card.Header>

                        <Card.Body>{status}</Card.Body>
                    </Card>
                );
            }}
            retry={() => {
                setRetryTime(Date.now());
                LevelSetInfoDecompressorApp = reload();
            }}
        >
            <LevelSetInfoDecompressorApp key={retryTime} {...props} />
        </SuspenseComponent>
    );
}

const root = document.getElementById('level-set-info-decompressor-root');
const downloadUrl = root?.dataset['downloadUrl'];
if (root && downloadUrl) {
    render(
        <LoadableLevelSetInfoDecompressorApp downloadUrl={downloadUrl} />,
        root
    );
}
