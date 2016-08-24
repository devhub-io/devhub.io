const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
    .webpack('app.js');
});

elixir.config.sourcemaps = false;

elixir(function (mix) {
    mix.styles([
            'owl.carousel.css',
            'style.css',
            'responsive.css'
        ], 'public/css/all.css')
        .scripts([
            'owl.carousel.min.js',
            'jquery.sticky.js',
            'jquery.easing.1.3.min.js',
            'main.js'
        ], 'public/js/app.js')
        .version(['css/all.css', 'js/app.js']);
});
