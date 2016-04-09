var elixir = require('laravel-elixir');

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

elixir(function(mix) {

    // Css tasks
    mix.sass('app.scss')
       .styles([
        '../../../public/css/app.css',
        // '../../../node_modules/react-photoswipe/lib/photoswipe.css'
    ]);

    // Js tasks
    mix.browserify('main.js')
       .scripts([
        '../../../node_modules/photoswipe/dist/photoswipe.min.js',
        '../../../node_modules/photoswipe/dist/photoswipe-ui-default.min.js',
        '../../../public/js/main.js',
        'pswp.js'
    ]);

    // Copy images
    mix.copy('resources/assets/images', 'public/images');

    // Versionning
    mix.version(['css/all.css', 'js/all.js']);

    // BrowserSync
    // mix.browserSync();
});
