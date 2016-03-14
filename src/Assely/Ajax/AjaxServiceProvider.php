<?php

namespace Assely\Ajax;

use Illuminate\Support\ServiceProvider;

class AjaxServiceProvider extends ServiceProvider
{
    /**
     * Register Ajax services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerActionsCollection();

        $this->registerDispatcher();
    }

    /**
     * Register collection of AJAX actions.
     *
     * @return void
     */
    public function registerActionsCollection()
    {
        $this->app->singleton(ActionsCollection::class, function ($app) {
            return new ActionsCollection;
        });

        $this->app->alias(ActionsCollection::class, 'actions');
    }

    /**
     * Register AJAX actions dispatcher.
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton(Dispatcher::class, function ($app) {
            return new Dispatcher($app['actions'], $app['router'], $app);
        });
    }
}
