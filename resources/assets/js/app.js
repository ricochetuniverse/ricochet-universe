import '../sass/app.scss';

import $ from 'jquery';

// Bootstrap
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/tooltip';

$('[data-toggle="tooltip"]').each(function() {
    const $base = $(this);

    const options = {};
    if ($base.closest('.navbar').length) {
        options.template = '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner text-nowrap"></div></div>';
    }
    $base.tooltip(options);
});

// Preact
if (process.env.NODE_ENV === 'development') {
    require('preact/debug');
}

// Website stuff
if (process.env.NODE_ENV === 'production') {
    require('./analytics');
}

import './round-info';
