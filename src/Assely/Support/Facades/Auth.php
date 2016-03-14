<?php

namespace Assely\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Auth\AuthManager
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'auth.manager';
    }
}
