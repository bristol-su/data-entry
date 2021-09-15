const mix = require('laravel-mix');

mix.setPublicPath('./public');

mix.js('resources/js/module.js', 'public/modules/data-entry/js/module.js').vue()
   .js('resources/js/components.js', 'public/modules/data-entry/js/components.js').vue()
    .sass('resources/sass/module.scss', 'public/modules/data-entry/css/module.css');

mix.webpackConfig({
    externals: {
        '@bristol-su/frontend-toolkit': 'Toolkit',
        'vue': 'Vue',
    }
});
