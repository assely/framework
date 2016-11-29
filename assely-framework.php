<?php
/*
Plugin Name: Assely Framework
Plugin URI: http://assely.org/
Description: Assely is a framework which brings a little joy to the WordPress development. Develop structured, easly scalable websites or web applications with expressive and elegant syntax.
Version: 0.1.0
Author: Assely
Author URI:  http://assely.org
License: MIT
License URI: https://opensource.org/licenses/MIT
Domain Path: /resources/lang
Text Domain: Assely
 */

// We need to define Assely framework
// paths on the plugin activation.
define('ASSELY_FRAMEWORK_DIR', plugin_dir_path(__FILE__));
define('ASSELY_FRAMEWORK_URI', plugin_dir_url(__FILE__));

// If we have composer vendor directory inside plugin directory,
// that means plugin was installed manually or directly form
// Wordpress Plugins repository. We need to autoload him.
if (
    is_dir(__DIR__.'/vendor')
    && ! class_exists('Assely\Foundation\Application')
) {
    require __DIR__.'/vendor/autoload.php';
}
