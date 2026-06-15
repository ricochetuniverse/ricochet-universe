import '../sass/app.scss';
import './images';

import './preact-debug';

import './bootstrap-init';
import './analytics';

// Page-specific components
if (document.getElementsByClassName('discordWidget__reactWrap').length) {
    void import('./discord/index');
}

if (document.getElementById('level-set-info-decompressor-root')) {
    void import('./level-set-info/index');
}

if (document.getElementsByClassName('js-open-round-info-modal').length) {
    void import('./round-info/index');
}

if (document.getElementById('decompressor-root')) {
    void import('./decompressor/index');
}

if (document.getElementById('red-mod-packager-root')) {
    void import('./red-mod-packager/index');
}

if (document.getElementById('image-to-canvas-root')) {
    void import('./image-to-canvas/index');
}
