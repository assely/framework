<?php

namespace Assely\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register asset services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAssetsCollection();

        $this->registerAssetFactory();
    }

    /**
     * Register collection of assets.
     *
     * @return void
     */
    public function registerAssetsCollection()
    {
        $this->app->singleton('assets.collection', function ($app) {
            return new AssetsCollection;
        });

        $this->app->alias('assets.collection', AssetsCollection::class);
    }

    /**
     * Register factory of assets.
     *
     * @return void
     */
    public function registerAssetFactory()
    {
        $this->app->singleton('asset.factory', function ($app) {
            return new AssetFactory($app['assets.collection'], $app);
        });

        $this->app->alias('asset.factory', AssetFactory::class);
    }
}
