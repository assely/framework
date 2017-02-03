<?php

namespace Assely\Routing;

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

        $this->registerWordPressConditions();

        $this->registerRouter();
    }

    /**
     * Register collection of routes.
     *
     * @return void
     */
    public function registerRoutesCollection()
    {
        $this->app->singleton('routes.collection', function ($app) {
            return new RoutesCollection;
        });

        $this->app->alias('routes.collection', RoutesCollection::class);
    }

    /**
     * Register collection of WordPress conditions.
     *
     * @return void
     */
    public function registerWordPressConditions()
    {
        $this->app->singleton('wpconditions', function ($app) {
            return new WordpressConditions;
        });

        $this->app->alias('wpconditions', WordpressConditions::class);
    }

    /**
     * Register router instance.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton('router', function ($app) {
            return new Router(
                $app['routes.collection'],
                $app['wpconditions'],
                $app
            );
        });

        $this->app->alias('router', Router::class);
    }
}
