import '../sass/app.scss';
import './images';

import 'bootstrap/js/src/collapse';
import 'bootstrap/js/src/dropdown';
import Tooltip from 'bootstrap/js/src/tooltip';

// Website stuff
import './analytics';

import './preact-debug';

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

document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((ele) => {
    const options: Partial<Tooltip.Options> = {};

    if (ele.closest('.navbar')) {
        options.template =
            '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner text-nowrap"></div></div>';
        options.fallbackPlacements = ['bottom'];
    }

    new Tooltip(ele, options);
});

document
    .querySelectorAll('.navbar [data-bs-toggle="dropdown"]')
    .forEach((dropdown) => {
        const tooltipEle = dropdown.querySelector('[data-bs-toggle="tooltip"]');
        if (!tooltipEle) {
            return;
        }

        const tooltipInstance = Tooltip.getInstance(tooltipEle);
        if (!tooltipInstance) {
            return;
        }

        dropdown.addEventListener('show.bs.dropdown', () => {
            tooltipEle.classList.add('show');
            tooltipInstance.hide();
            tooltipInstance.disable();
        });

        dropdown.addEventListener('hide.bs.dropdown', () => {
            tooltipEle.classList.remove('show');
            tooltipInstance.enable();
        });
    });
