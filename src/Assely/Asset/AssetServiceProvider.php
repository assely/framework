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
        $this->app->singleton(AssetsCollection::class, function ($app) {
            return new AssetsCollection;
        });

        $this->app->alias(AssetsCollection::class, 'assets');
    }

    /**
     * Register factory of assets.
     *
     * @return void
     */
    public function registerAssetFactory()
    {
        $this->app->singleton(AssetFactory::class, function ($app) {
            return new AssetFactory($app['assets'], $app);
        });

        $this->app->alias(AssetFactory::class, 'asset');
    }
}
