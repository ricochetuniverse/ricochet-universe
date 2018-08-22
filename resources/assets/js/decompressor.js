// noinspection ES6UnusedImports
import {h, render} from 'preact';
import Loadable from 'react-loadable';

import LoadingComponent from './LoadingComponent';

const LoadableDecompressorApp = Loadable({
    loader: () => import('./DecompressorApp'),
    loading(props) {
        return (
            <div className="card mb-3">
                <div className="card-header">Decompressor</div>

                <div className="card-body">
                    <p className="m-0">
                        <LoadingComponent {...props} />
                    </p>
                </div>
            </div>
        );
    },
    timeout: 5000,
});

const root = document.getElementById('decompressor-root');

if (root) {
    render(<LoadableDecompressorApp/>, root);
}
