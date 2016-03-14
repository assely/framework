var elixir = require('laravel-elixir');

elixir(function(mix) {
	// Mix Assely styles.
    mix.sass('app.scss', './public/css/assely.css');

    // Mix Parsley.js to the vendors directory.
    mix.scripts([
    	'./node_modules/parsleyjs/dist/parsley.js',
    	'./node_modules/parsleyjs/dist/i18n/pl.js'
    ], './public/js/vendors/parsley.js');

    // Mix Vue.js to the vendors directory.
    mix.scripts([
    	'./node_modules/vue/dist/vue.js'
    ], './public/js/vendors/vue.js');

    // Mix Assely base components for admin.
    mix.scriptsIn('./resources/assets/js/Components', './public/js/assely-components.js');

    // Run php tests.
    mix.phpUnit(null, 'bin/phpunit');
});