import {render} from 'preact';
import {lazy} from 'preact/compat';
import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';

import SuspenseComponent from '../SuspenseComponent';

let RedModCreatorApp = reload();

function reload() {
    return lazy(() => import('./RedModPackagerApp'));
}

function LoadableRedModCreatorApp() {
    const [retryTime, setRetryTime] = useState(0);

    return (
        <SuspenseComponent
            fallback={(status) => {
                return (
                    <Card className="mb-3">
                        <Card.Header>RED mod packager</Card.Header>

                        <Card.Body>{status}</Card.Body>
                    </Card>
                );
            }}
            retry={() => {
                setRetryTime(Date.now());
                RedModCreatorApp = reload();
            }}
        >
            <RedModCreatorApp key={retryTime} />
        </SuspenseComponent>
    );
}

const root = document.getElementById('red-mod-packager-root');

if (root) {
    render(<LoadableRedModCreatorApp />, root);
}
