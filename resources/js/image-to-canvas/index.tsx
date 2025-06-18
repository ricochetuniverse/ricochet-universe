import {render} from 'preact';
import Card from 'react-bootstrap/Card';
import Loadable from 'react-loadable';

import LoadingComponent from '../LoadingComponent';

const LoadableImageToCanvasApp = Loadable({
    loader: () => import('./ImageToCanvasApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <Card.Header>Image to Canvas</Card.Header>

                <Card.Body>
                    <LoadingComponent {...props} />
                </Card.Body>
            </Card>
        );
    },
    timeout: 5000,
});

const root = document.getElementById('image-to-canvas-root');

if (root) {
    render(<LoadableImageToCanvasApp />, root);
}
