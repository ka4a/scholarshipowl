let mix = require('laravel-mix');

// Front
mix.sass('resources/assets/sass/front.scss', 'public/css');

mix.js([
    'resources/assets/js/front.js',
], 'public/js/front.js');

mix.combine([
    'node_modules/jquery.mmenu/dist/jquery.mmenu.css',
    'node_modules/jquery.mmenu/dist/extensions/positioning/jquery.mmenu.positioning.css',
    'node_modules/jquery.mmenu/dist/extensions/shadows/jquery.mmenu.shadows.css',
    'node_modules/jquery.mmenu/dist/extensions/fullscreen/jquery.mmenu.fullscreen.css',
    'node_modules/jquery.mmenu/dist/addons/navbars/jquery.mmenu.navbars.css',
    'node_modules/vegas/dist/vegas.css',
], 'public/css/libs.css');
