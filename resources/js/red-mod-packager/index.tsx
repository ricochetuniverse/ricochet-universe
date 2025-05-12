import {render} from 'preact';
import Card from 'react-bootstrap/Card';
import Loadable from 'react-loadable';

import LoadingComponent from '../LoadingComponent';

const LoadableRedModCreatorApp = Loadable({
    loader: () => import('./RedModPackagerApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <Card.Header>RED mod packager</Card.Header>

                <Card.Body>
                    <p className="m-0">
                        <LoadingComponent {...props} />
                    </p>
                </Card.Body>
            </Card>
        );
    },
    timeout: 5000,
});

const root = document.getElementById('red-mod-packager-root');

if (root) {
    render(<LoadableRedModCreatorApp />, root);
}
