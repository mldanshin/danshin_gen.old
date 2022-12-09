const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/app.scss', "public/style/app.css")
    .options({ processCssUrls: false })
    .version();
mix.sass('resources/sass/auth.scss', 'public/style/auth.css').version();
mix.js('resources/js/app.js', "public/js/app.js").version();
mix.js('resources/js/auth.js', 'public/js/auth.js').version();
