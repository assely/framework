<?php

namespace Assely\Rewrite;

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
        $this->registerRewritesCollection();

        $this->registerRewriteFactory();
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
}
