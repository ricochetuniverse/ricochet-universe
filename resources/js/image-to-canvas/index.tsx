import {render} from 'preact';
import {lazy} from 'preact/compat';
import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';

import SuspenseComponent from '../SuspenseComponent';

let ImageToCanvasApp = reload();

function reload() {
    return lazy(() => import('./ImageToCanvasApp'));
}

function LoadableImageToCanvasApp() {
    const [retryTime, setRetryTime] = useState(0);

    return (
        <SuspenseComponent
            fallback={(status) => {
                return (
                    <Card className="mb-3">
                        <Card.Header>Image to canvas</Card.Header>

                        <Card.Body>{status}</Card.Body>
                    </Card>
                );
            }}
            retry={() => {
                setRetryTime(Date.now());
                ImageToCanvasApp = reload();
            }}
        >
            <ImageToCanvasApp key={retryTime} />
        </SuspenseComponent>
    );
}

const root = document.getElementById('image-to-canvas-root');

if (root) {
    render(<LoadableImageToCanvasApp />, root);
}
