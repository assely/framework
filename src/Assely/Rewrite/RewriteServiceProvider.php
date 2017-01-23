<?php

namespace Assely\Routing;

use Illuminate\Support\ServiceProvider;

class RewriteServiceProvider extends ServiceProvider
{
    /**
     * Register routing services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutesCollection();
    }

    /**
     * Register collection of routes.
     *
     * @return void
     */
    public function registerRoutesCollection()
    {
        $this->app->singleton('rewrites.factory', function ($app) {
            return new RewritesFactory($app);
        });

        $this->app->alias('rewrites.factory', RewritesFactory::class);
    }
}
