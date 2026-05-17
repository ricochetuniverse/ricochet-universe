import {render} from 'preact';

import ImageToCanvasApp from './ImageToCanvasApp';

const root = document.getElementById('image-to-canvas-root');

if (root) {
    render(<ImageToCanvasApp />, root);
}
