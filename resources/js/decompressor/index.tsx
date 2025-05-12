import {render} from 'preact';
import Card from 'react-bootstrap/Card';
import Loadable from 'react-loadable';

import LoadingComponent from '../LoadingComponent';

const LoadableDecompressorApp = Loadable({
    loader: () => import('./DecompressorApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <Card.Header>Decompressor</Card.Header>

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

const root = document.getElementById('decompressor-root');

if (root) {
    render(<LoadableDecompressorApp />, root);
}
