<?php

namespace Assely\Pagination;

use Illuminate\Support\ServiceProvider;

class PaginationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register pagination services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Pagination::class, function () {
            return new Pagination;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Pagination::class];
    }
}
