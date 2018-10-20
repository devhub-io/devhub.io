const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .scripts([
        'resources/assets/js/wow.min.js',
        'resources/assets/js/jquery.sticky.js',
        'resources/assets/js/main.js'
    ], 'public/js/app.js')
    .styles([
        'resources/assets/css/normalize.css',
        'resources/assets/css/animate.min.css',
        'resources/assets/css/style.css',
    ], 'public/css/all.css');

if (mix.inProduction()) {
    mix.version();
}
