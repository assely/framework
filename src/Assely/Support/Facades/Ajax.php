<?php

namespace Assely\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Ajax\Dispacher
 */
class Ajax extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'ajax.dispatcher';
    }
}
