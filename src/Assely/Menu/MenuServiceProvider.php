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
        $this->app->singleton(MenusCollection::class, function () {
            return new MenusCollection;
        });

        $this->app->alias(MenusCollection::class, 'menus');
    }

    /**
     * Register factory of menus.
     *
     * @return void
     */
    public function registerMenuFactory()
    {
        $this->app->singleton(MenuFactory::class, function ($app) {
            return new MenuFactory($app['menus'], $app);
        });
    }
}
