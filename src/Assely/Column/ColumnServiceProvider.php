<?php

namespace Assely\Column;

use Illuminate\Support\ServiceProvider;

class ColumnServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register column services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('column.factory', function ($app) {
            return new ColumnFactory($app);
        });

        $this->app->alias('column.factory', ColumnFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['column.factory'];
    }
}
