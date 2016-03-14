<?php

namespace Assely\Taxonomy;

use Illuminate\Support\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * Register taxonomy services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('taxonomy.factory', function ($app) {
            return new TaxonomyFactory($app);
        });

        $this->app->alias('taxonomy.factory', TaxonomyFactory::class);
    }
}
