import '../sass/app.scss';

import $ from 'jquery';
import 'bootstrap';

// Bootstrap
$('[data-toggle="tooltip"]').tooltip();

// Preact
if (process.env.NODE_ENV === 'development') {
    require('preact/debug');
}

// Website stuff
import './round-info';
