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
        $this->registerTermAdapter();
        $this->registerUserAdapter();
        $this->registerMenuAdapter();
        $this->registerCommentAdapter();
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

    /**
     * Register Term Adapter.
     *
     * @return void
     */
    protected function registerTermAdapter()
    {
        $this->app->bind('adapter.term', function ($app) {
            return new Term($app['config']);
        });

        $this->app->alias('adapter.term', Term::class);
    }

    /**
     * Register User Adapter.
     *
     * @return void
     */
    protected function registerUserAdapter()
    {
        $this->app->bind('adapter.user', function ($app) {
            return new User($app['config']);
        });

        $this->app->alias('adapter.user', User::class);
    }

    /**
     * Register Comment Adapter.
     *
     * @return void
     */
    protected function registerCommentAdapter()
    {
        $this->app->bind('adapter.comment', function ($app) {
            return new Comment($app['config']);
        });

        $this->app->alias('adapter.comment', Comment::class);
    }

    /**
     * Register Menu Adapter.
     *
     * @return void
     */
    protected function registerMenuAdapter()
    {
        $this->app->bind('adapter.menu', function ($app) {
            return new Menu($app['config']);
        });

        $this->app->alias('adapter.menu', Menu::class);
    }
}
