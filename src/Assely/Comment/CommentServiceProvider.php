<?php

namespace Assely\Comment;

use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    /**
     * Register comment services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('comment.factory', function ($app) {
            return new CommentFactory($app);
        });

        $this->app->alias('comment.factory', CommentFactory::class);
    }
}
