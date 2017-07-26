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

elixir.config.sourcemaps = false;
elixir(function (mix) {
    mix
        .styles([
            'normalize.css',
            'animate.min.css',
            'style.css',
        ], 'public/css/all.css')
        .scripts([
            'wow.min.js',
            'jquery.sticky.js',
            'main.js'
        ], 'public/js/app.js')
        .version(['css/all.css', 'js/app.js']);
});
