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
        $this->app->bind('fields.collection', 'Assely\Field\FieldsCollection');

        $this->app->bind('field.manager', 'Assely\Field\FieldManager');

        $this->app->bind('field.validator', 'Assely\Field\FieldValidator');
    }
}
