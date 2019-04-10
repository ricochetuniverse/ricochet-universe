import {h, render} from 'preact';
import Loadable from 'react-loadable';
import {Card, CardBody, CardHeader} from 'reactstrap';

import LoadingComponent from '../LoadingComponent';

const LoadableRedModCreatorApp = Loadable({
    loader: () => import('./RedModPackagerApp'),
    loading(props) {
        return (
            <Card className="mb-3">
                <CardHeader>RED mod packager</CardHeader>

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

const root = document.getElementById('red-mod-packager-root');

if (root) {
    render(<LoadableRedModCreatorApp />, root);
}
