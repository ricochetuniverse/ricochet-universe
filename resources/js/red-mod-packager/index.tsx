import {render} from 'preact';

import RedModPackagerApp from './RedModPackagerApp';

const root = document.getElementById('red-mod-packager-root');

if (root) {
    render(<RedModPackagerApp />, root);
}
