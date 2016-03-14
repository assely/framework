<?php

namespace Assely\View;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register view services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('view.factory', 'Assely\View\ViewFactory');

        $this->app->singleton('Assely\View\Directives\Shortcodes');

        $this->registerDirectives();
    }

    /**
     * Register Wordpress Blade directives.
     *
     * @return void
     */
    public function registerDirectives()
    {
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
            return '<?php wp_head(); ?>';
        });

        Blade::directive('wpfooter', function () {
            return '<?php wp_footer(); ?>';
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
