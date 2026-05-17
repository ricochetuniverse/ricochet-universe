import {render} from 'preact';

import DecompressorApp from './DecompressorApp';

const root = document.getElementById('decompressor-root');

if (root) {
    render(
        <DecompressorApp
            dotnetLoaderUrl={root.dataset['dotnetLoaderUrl'] ?? ''}
        />,
        root
    );
}
