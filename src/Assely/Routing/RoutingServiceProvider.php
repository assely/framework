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

        $this->registerRoute();
    }

    /**
     * Register collection of routes.
     *
     * @return void
     */
    public function registerRoutesCollection()
    {
        $this->app->singleton(RoutesCollection::class, function ($app) {
            return new RoutesCollection;
        });

        $this->app->alias(RoutesCollection::class, 'routes');
    }

    /**
     * Register collection of WordPress conditions.
     *
     * @return void
     */
    public function registerWordPressConditions()
    {
        $this->app->singleton(WordpressConditions::class, function ($app) {
            return new WordpressConditions;
        });

        $this->app->alias(WordpressConditions::class, 'wpconditions');
    }

    /**
     * Register router instance.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton(Router::class, function ($app) {
            return new Router($app['routes'], $app['wpconditions'], $app);
        });

        $this->app->alias(Router::class, 'router');
    }

    /**
     * Register router instance.
     *
     * @return void
     */
    public function registerRoute()
    {
        $this->app->bind(Route::class, function ($app) {
            return new Route($app['router'], $app['hook'], $app);
        });

        $this->app->alias(Route::class, 'route');
    }
}
