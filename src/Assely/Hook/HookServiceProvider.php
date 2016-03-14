<?php

namespace Assely\Hook;

use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Register hook services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hook.factory', function ($app) {
            return new HookFactory($app);
        });

        $this->app->alias('hook.factory', HookFactory::class);
    }
}
