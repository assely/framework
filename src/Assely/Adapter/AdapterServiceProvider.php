<?php

namespace Assely\Adapter;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    /**
     * Register adapter services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdapterPlugger();

        $this->registerPostAdapter();

        $this->app->bind('adapter.user', 'Assely\Adapter\User');

        $this->app->bind('adapter.term', 'Assely\Adapter\Term');

        $this->app->bind('adapter.image', 'Assely\Adapter\Image');

        $this->app->bind('adapter.comment', 'Assely\Adapter\Comment');

        $this->app->bind('adapter.menu', 'Assely\Adapter\Menu');
    }

    /**
     * Register Adapter Plugger.
     *
     * @return void
     */
    protected function registerAdapterPlugger()
    {
        $this->app->bind('adapter.plugger', function ($app) {
            $collection = new Collection;

            return new AdapterPlugger($collection, $app['config']);
        });

        $this->app->alias('adapter.plugger', AdapterPlugger::class);
    }

    /**
     * Register Post Adapter.
     *
     * @return void
     */
    protected function registerPostAdapter()
    {
        $this->app->bind('adapter.post', function ($app) {
            return new Post($app['config']);
        });

        $this->app->alias('adapter.post', Post::class);
    }
}
