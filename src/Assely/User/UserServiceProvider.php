<?php

namespace Assely\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register user services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserFactory::class, function ($app) {
            return new UserFactory($app);
        });
    }
}
