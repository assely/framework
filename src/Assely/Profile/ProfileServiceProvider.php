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
        $this->app->singleton('profile.factory', function ($app) {
            return new ProfileFactory($app);
        });

        $this->app->alias('profile.factory', ProfileFactory::class);
    }
}
