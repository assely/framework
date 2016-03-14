<?php

namespace Assely\Field;

use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Register field services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFieldsFinder();

        $this->registerFieldsCollection();

        $this->registerFieldValidator();

        $this->registerFieldManager();
    }

    /**
     * Register fields finder.
     *
     * @return void
     */
    public function registerFieldsFinder()
    {
        $this->app->bind('fields.finder', function () {
            return new FieldsFinder;
        });

        $this->app->alias('fields.finder', FieldsFinder::class);
    }

    /**
     * Register fields collection.
     *
     * @return void
     */
    public function registerFieldsCollection()
    {
        $this->app->bind('fields.collection', function ($app) {
            return new FieldsCollection($app['fields.finder']);
        });

        $this->app->alias('fields.collection', FieldsCollection::class);
    }

    /**
     * Register field validator.
     *
     * @return void
     */
    public function registerFieldValidator()
    {
        $this->app->bind('field.validator', function () {
            return new FieldValidator;
        });

        $this->app->alias('field.validator', FieldValidator::class);
    }

    /**
     * Register field manager.
     *
     * @return void
     */
    public function registerFieldManager()
    {
        $this->app->bind('field.manager', function ($app) {
            return new FieldManager(
                $app['hook.factory'],
                $app['asset.factory'],
                $app['view'],
                $app['field.validator']
            );
        });

        $this->app->alias('field.manager', FieldManager::class);
    }
}
