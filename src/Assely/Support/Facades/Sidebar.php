<?php

namespace Assely\Support\Facades;

use Assely\Sidebar\SidebarFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Sidebar\SidebarFactory
 */
class Sidebar extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'sidebar.factory';
    }
}
