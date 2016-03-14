<?php

namespace Assely\Posttype;

use Illuminate\Support\ServiceProvider;

class PosttypeServiceProvider extends ServiceProvider
{
    /**
     * Register posttype services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('posttype.factory', function ($app) {
            return new PosttypeFactory($app);
        });

        $this->app->alias('posttype.factory', PosttypeFactory::class);
    }
}
