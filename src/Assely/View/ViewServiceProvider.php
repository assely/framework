<?php

namespace Assely\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewServiceProvider as ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register view services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->registerDirectives();

        $this->registerViewComposers();
    }

    /**
     * Register view factory.
     *
     * @return void
     */
    public function registerViewComposers()
    {
        $this->app['view']->composer('Assely::script', function ($view) {
            $view->with('debug', WP_DEBUG);
        });
    }

    /**
     * Register Wordpress Blade directives.
     *
     * @return void
     */
    public function registerDirectives()
    {
        $this->app->singleton('Assely\View\Directives\Shortcodes');

        $this->registerHeadAndFooterDirectives();

        $this->registerShortcodeDirective();
    }

    /**
     * Register wp_head() and wp_footer() functions directives.
     *
     * @return void
     */
    public function registerHeadAndFooterDirectives()
    {
        Blade::directive('wphead', function () {
            return "<?php do_action('get_header'); wp_head(); ?>";
        });

        Blade::directive('wpfooter', function () {
            return "<?php do_action('get_footer'); wp_footer(); ?>";
        });
    }

    /**
     * Register do_shortcode() function directive.
     *
     * @return void
     */
    public function registerShortcodeDirective()
    {
        Blade::directive('shortcodes', function () {
            return "<?php Assely\Helpers\app('Assely\View\Directives\Shortcodes')->setUp(); ?>";
        });

        Blade::directive('endshortcodes', function () {
            return "<?php echo Assely\Helpers\app('Assely\View\Directives\Shortcodes')->tearDown(); ?>";
        });
    }
}
