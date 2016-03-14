<?php

namespace Assely\Sidebar;

use Illuminate\Support\ServiceProvider;

class SidebarServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register sidebar services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSidebarsCollection();

        $this->registerSidebarFactory();
    }

    /**
     * Registers collection of sidebars.
     *
     * @return void
     */
    public function registerSidebarsCollection()
    {
        $this->app->singleton('sidebars.collection', function ($app) {
            return new SidebarsCollection;
        });

        $this->app->alias('sidebars.collection', SidebarsCollection::class);
    }

    /**
     * Registers sidebars factory.
     *
     * @return void
     */
    public function registerSidebarFactory()
    {
        $this->app->singleton('sidebar.factory', function ($app) {
            return new SidebarFactory($app['sidebars.collection'], $app);
        });

        $this->app->alias('sidebar.factory', SidebarFactory::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sidebars.collection', 'sidebar.factory'];
    }
}
