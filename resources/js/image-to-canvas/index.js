// @flow

import {render} from 'preact';
import Loadable from 'react-loadable';
import {Card, CardBody, CardHeader} from 'reactstrap';

import LoadingComponent from '../LoadingComponent';

const LoadableImageToCanvasApp = Loadable({
    loader: () => import('./ImageToCanvasApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <CardHeader>Image to Canvas</CardHeader>

                <CardBody>
                    <p className="m-0">
                        <LoadingComponent {...props} />
                    </p>
                </CardBody>
            </Card>
        );
    },
    timeout: 5000,
});

const root = document.getElementById('image-to-canvas-root');

if (root) {
    render(<LoadableImageToCanvasApp />, root);
}
