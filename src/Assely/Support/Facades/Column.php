<?php

namespace Assely\Support\Facades;

use Assely\Column\ColumnFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Column\ColumnFactory
 */
class Column extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return ColumnFactory::class;
    }
}
