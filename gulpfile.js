var elixir = require('laravel-elixir');

elixir(function(mix) {

	// Mix Assely styles.
    mix.sass('app.scss', './public/css/assely.css');

    // Copy Vue.js to the vendors directory.
    mix.copy('./node_modules/vue/dist/vue.min.js', './public/js/vendors/vue.min.js');

    // Mix Assely base components for admin.
    mix.scriptsIn('./resources/assets/js/Components', './public/js/assely-components.js');

    // Run php tests.
    mix.phpUnit(null, 'bin/phpunit');

});