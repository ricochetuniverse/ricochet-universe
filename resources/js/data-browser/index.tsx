import {render} from 'preact';

import DataBrowserApp from './DataBrowserApp';

const root = document.getElementById('data-browser-root');

if (root) {
    render(<DataBrowserApp />, root);
}
