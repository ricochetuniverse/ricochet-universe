import '../sass/app.scss';

import $ from 'jquery';

// Bootstrap
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/tooltip';

$('[data-toggle="tooltip"], .js-with-tooltip').each(function () {
    const $base = $(this);

    const options = {};
    if ($base.closest('.navbar').length) {
        options.template =
            '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner text-nowrap"></div></div>';
    }
    $base.tooltip(options);
});

$('[data-toggle="dropdown"]').each(function () {
    $(this)
        .parent()
        .on('show.bs.dropdown', (ev) => {
            $(ev.relatedTarget).tooltip('hide').tooltip('disable');
        })
        .on('hide.bs.dropdown', (ev) => {
            $(ev.relatedTarget).tooltip('enable');
        });
});

// Preact
if (process.env.NODE_ENV === 'development') {
    require('preact/debug');
}

// Website stuff
import './analytics';

import './discord/index';
import './round-info/index';
import './decompressor/index';
import './red-mod-packager/index';
