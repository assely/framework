<?php

namespace Assely\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register menu services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMenusCollection();

        $this->registerMenuFactory();
    }

    /**
     * Register collection of menus.
     *
     * @return void
     */
    public function registerMenusCollection()
    {
        $this->app->singleton('menus.collection', function () {
            return new MenusCollection;
        });

        $this->app->alias('menus.collection', MenusCollection::class);
    }

    /**
     * Register factory of menus.
     *
     * @return void
     */
    public function registerMenuFactory()
    {
        $this->app->singleton('menu.factory', function ($app) {
            return new MenuFactory($app['menus.collection'], $app);
        });

        $this->app->alias('menu.factory', MenuFactory::class);
    }
}
