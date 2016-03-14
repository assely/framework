<?php

namespace Assely\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register column services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('auth.manager', 'Assely\Auth\AuthManager');
    }
}
