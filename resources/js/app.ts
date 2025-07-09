import '../sass/app.scss';

// @ts-expect-error loading Bootstrap components
// eslint-disable-next-line @typescript-eslint/no-unused-vars
import {Collapse, Dropdown, Tooltip} from 'bootstrap';

// Website stuff
import './analytics';

import './preact-debug';
import './discord/index';
import './round-info/index';
import './decompressor/index';
import './red-mod-packager/index';
import './image-to-canvas/index';

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
