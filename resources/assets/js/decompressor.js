// noinspection ES6UnusedImports
import {h, render} from 'preact';
import Loadable from 'react-loadable';
import {Card, CardBody, CardHeader} from 'reactstrap';

import LoadingComponent from './LoadingComponent';

const LoadableDecompressorApp = Loadable({
    loader: () => import('./DecompressorApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <CardHeader>Decompressor</CardHeader>

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

const root = document.getElementById('decompressor-root');

if (root) {
    render(<LoadableDecompressorApp/>, root);
}
