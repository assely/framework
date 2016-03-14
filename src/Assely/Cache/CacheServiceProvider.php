<?php

namespace Assely\Cache;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register cache services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCacheService();

        $this->registerBladeDirective();
    }

    /**
     * Register cache service.
     *
     * @return void
     */
    public function registerCacheService()
    {
        $this->app->singleton('cache', function ($app) {
            return new Cache($app['config']);
        });

        $this->app->alias('cache', Cache::class);
    }

    /**
     * Register @cache blade directive
     *
     * @return void
     */
    public function registerBladeDirective()
    {
        $this->app->singleton(BladeDirective::class);
    }

    /**
     * Bootstrap cache services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('cache', function ($cache) {
            return "<?php if ( ! Assely\Helpers\app('Assely\Cache\BladeDirective')->setUp({$cache})) : ?>";
        });

        Blade::directive('endcache', function () {
            return "<?php endif; echo Assely\Helpers\app('Assely\Cache\BladeDirective')->tearDown(); ?>";
        });
    }
}
