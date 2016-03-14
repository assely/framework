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
        $this->app->singleton(PosttypeFactory::class, function ($app) {
            return new PosttypeFactory($app);
        });
    }
}
