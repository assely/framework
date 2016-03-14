<?php

namespace Assely\Thumbnail;

use Illuminate\Support\ServiceProvider;

class ThumbnailServiceProvider extends ServiceProvider
{
    /**
     * Register thumbnail services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('thumbnails', function () {
            return new ThumbnailsCollection;
        });

        $this->app->singleton(ThumbnailFactory::class, function ($app) {
            return new ThumbnailFactory($app['thumbnails'], $app);
        });
    }
}
