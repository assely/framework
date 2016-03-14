<?php

namespace Assely\Support\Facades;

use Assely\Routing\Router;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Routing\Router
 */
class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'router';
    }
}
