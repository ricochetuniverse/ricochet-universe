import 'bootstrap/js/src/collapse';
import 'bootstrap/js/src/dropdown';
import Tooltip from 'bootstrap/js/src/tooltip';

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
