import '../sass/app.scss';
import './images';

import './preact-debug';

import './bootstrap-init';
import './analytics';

// Page-specific components
if (document.getElementsByClassName('discordWidget__reactWrap').length) {
    import('./discord/index');
}

if (document.getElementById('level-set-info-decompressor-root')) {
    import('./level-set-info/index');
}

if (document.getElementsByClassName('js-open-round-info-modal').length) {
    import('./round-info/index');
}

if (document.getElementById('decompressor-root')) {
    import('./decompressor/index');
}

if (document.getElementById('red-mod-packager-root')) {
    import('./red-mod-packager/index');
}

if (document.getElementById('image-to-canvas-root')) {
    import('./image-to-canvas/index');
}
