<?php

namespace Assely\Nonce;

use Illuminate\Support\ServiceProvider;

class NonceServiceProvider extends ServiceProvider
{
    /**
     * Register nonce services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('nonce', 'Assely\Nonce\NonceFactory');
    }
}
