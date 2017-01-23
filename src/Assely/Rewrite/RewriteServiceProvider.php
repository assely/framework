<?php

namespace Assely\Routing;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
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
