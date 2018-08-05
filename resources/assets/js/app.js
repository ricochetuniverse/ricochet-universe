import '../sass/app.scss';

import $ from 'jquery';

// Bootstrap
import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/tooltip';
$('[data-toggle="tooltip"]').tooltip();

// Preact
if (process.env.NODE_ENV === 'development') {
    require('preact/debug');
}

// Website stuff
import './round-info';
