window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
    require('jquery.mmenu');
    require('jquery.mmenu/dist/addons/navbars/_jquery.mmenu.navbar.close');
    require('jquery.mmenu/dist/addons/fixedelements/jquery.mmenu.fixedelements');
    require('vegas');
    window.List = require('list.js');

} catch (e) {}
