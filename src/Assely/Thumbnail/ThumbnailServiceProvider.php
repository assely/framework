<?php

namespace Assely\Thumbnail;

use Illuminate\Support\ServiceProvider;

class ThumbnailServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register thumbnail services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerThumbnailsCollection();

        $this->registerThumbnailFactory();
    }

    /**
     * Register thumbnails collection.
     *
     * @return void
     */
    public function registerThumbnailsCollection()
    {
        $this->app->singleton('thumbnails.collection', function () {
            return new ThumbnailsCollection;
        });

        $this->app->alias('thumbnails.collection', ThumbnailsCollection::class);
    }

    /**
     * Register thumbnail factory.
     *
     * @return void
     */
    public function registerThumbnailFactory()
    {
        $this->app->singleton('thumbnail.factory', function ($app) {
            return new ThumbnailFactory($app['thumbnails.collection'], $app);
        });

        $this->app->alias('thumbnail.factory', ThumbnailFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['thumbnails.collection', 'thumbnail.factory'];
    }
}
