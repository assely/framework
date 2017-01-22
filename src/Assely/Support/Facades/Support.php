<?php

namespace Assely\Support\Facades;

use Assely\Support\Supports;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Support\Support
 */
class Support extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return Supports::class;
    }
}
