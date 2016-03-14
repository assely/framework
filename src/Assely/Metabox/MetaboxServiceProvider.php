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
        $this->app->singleton('metabox.factory', function ($app) {
            return new MetaboxFactory($app);
        });

        $this->app->alias('metabox.factory', MetaboxFactory::class);
    }
}
