require('bootstrap-notify')
require('./bootstrap');
require('../../vendor/almasaeed2010/adminlte/dist/js/adminlte');

//require('alpinejs');
require('overlayscrollbars');
require('datatables.net');
require('datatables.net');
require('datatables.net-bs4');
require('datatables.net-responsive');
require('datatables.net-rowreorder');
require('popper.js');
require('bootstrap');
global.moment = require('moment');
import 'moment-timezone';
require('tempusdominus-bootstrap-4');
require('bootstrap-show-modal');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


require('./notify');
require('./dashboard');
require('./fleet');


