<?php

namespace Assely\Support\Facades;

use Assely\Menu\MenuFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Menu\Menu
 */
class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return MenuFactory::class;
    }
}
