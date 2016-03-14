<?php

namespace Assely\Metabox;

use Illuminate\Support\ServiceProvider;

class MetaboxServiceProvider extends ServiceProvider
{
    /**
     * Register metabox services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MetaboxFactory::class, function ($app) {
            return new MetaboxFactory($app);
        });
    }
}
