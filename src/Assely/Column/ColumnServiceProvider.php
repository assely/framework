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
        $this->app->singleton(ColumnFactory::class, function ($app) {
            return new ColumnFactory($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ColumnFactory::class];
    }
}
