<?php

namespace Assely\Rewrite;

use Illuminate\Support\ServiceProvider;

class RewriteServiceProvider extends ServiceProvider
{
    /**
     * Register rewrite services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRewritesCollection();

        $this->registerRewriteFactory();

        $this->registerEndpoint();
    }

    /**
     * Register collection of rewrites.
     *
     * @return void
     */
    public function registerRewritesCollection()
    {
        $this->app->singleton('rewrites.collection', function () {
            return new RewritesCollection;
        });

        $this->app->alias('rewrites.collection', RewritesCollection::class);
    }

    /**
     * Register rewrite factory.
     *
     * @return void
     */
    public function registerRewriteFactory()
    {
        $this->app->singleton('rewrite.factory', function ($app) {
            return new RewriteFactory($app['rewrites.collection'], $app);
        });

        $this->app->alias('rewrite.factory', RewriteFactory::class);
    }

    /**
     * Register rewrite factory.
     *
     * @return void
     */
    public function registerEndpoint()
    {
        $this->app->bind('endpoint', function ($app) {
            return new Endpoint($app['hook.factory']);
        });

        $this->app->alias('endpoint', Endpoint::class);
    }
}
