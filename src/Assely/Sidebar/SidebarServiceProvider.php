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
        $this->app->singleton(SidebarsCollection::class, function ($app) {
            return new SidebarsCollection;
        });

        $this->app->alias(SidebarsCollection::class, 'sidebars');

        $this->app->singleton(SidebarFactory::class, function () {
            return new SidebarFactory($app['sidebars'], $app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            SidebarsCollection::class,
            SidebarFactory::class,
        ];
    }
}
