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
        $this->app->singleton('ajaxes.collection', function ($app) {
            return new ActionsCollection;
        });

        $this->app->alias('ajaxes.collection', ActionsCollection::class);
    }

    /**
     * Register AJAX actions dispatcher.
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton('ajax.dispatcher', function ($app) {
            return new Dispatcher(
                $app['ajaxes.collection'],
                $app['router'],
                $app
            );
        });

        $this->app->alias('ajax.dispatcher', Dispatcher::class);
    }
}
