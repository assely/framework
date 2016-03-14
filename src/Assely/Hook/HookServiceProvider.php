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
        $this->app->singleton('hook', 'Assely\Hook\HookFactory');
    }
}
