const mix = require('laravel-mix');

mix.setPublicPath('./public');

mix.js('resources/js/module.js', 'public/modules/data-entry/js')
    .sass('resources/sass/module.scss', 'public/modules/data-entry/css');
