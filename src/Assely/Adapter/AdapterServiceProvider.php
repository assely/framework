<?php

namespace Assely\Adapter;

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
        $this->app->bind('adapter.user', 'Assely\Adapter\User');

        $this->app->bind('adapter.post', 'Assely\Adapter\Post');

        $this->app->bind('adapter.term', 'Assely\Adapter\Term');

        $this->app->bind('adapter.image', 'Assely\Adapter\Image');

        $this->app->bind('adapter.comment', 'Assely\Adapter\Comment');

        $this->app->bind('adapter.menu', 'Assely\Adapter\Menu');
    }
}
