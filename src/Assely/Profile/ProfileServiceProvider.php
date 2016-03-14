<?php

namespace Assely\Profile;

use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    /**
     * Register profile services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProfileFactory::class, function ($app) {
            return new ProfileFactory($app);
        });
    }
}
