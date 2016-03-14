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
        $this->app->bind('adapter.user', 'Assely\Adapter\UserAdapter');

        $this->app->bind('adapter.post', 'Assely\Adapter\PostAdapter');

        $this->app->bind('adapter.term', 'Assely\Adapter\TermAdapter');

        $this->app->bind('adapter.image', 'Assely\Adapter\Image');

        $this->app->bind('adapter.comment', 'Assely\Adapter\CommentAdapter');

        $this->app->bind('adapter.menu', 'Assely\Adapter\MenuAdapter');
    }
}
