import Card from 'react-bootstrap/Card';
import Loadable from 'react-loadable';

import LoadingComponent from '../LoadingComponent';

export default Loadable({
    loader: () =>
        import(
            /* webpackChunkName: "decompressor-editor" */ './DecompressorEditor'
        ),
    loading(props) {
        return (
            <Card.Body>
                <LoadingComponent {...props} text="Loading text viewer..." />
            </Card.Body>
        );
    },
    timeout: 10000,
});
