<?php

namespace Assely\Sidebar;

use Assely\Singularity\Manager;

class SidebarManager extends Manager
{
    /**
     * Bootstrap sidebar manager.
     *
     * @param \Assely\Sidebar\Sidebar $sidebar
     *
     * @return void
     */
    public function boot($sidebar)
    {
        $this->sidebar = $sidebar;

        $this->hook->action(
            'widgets_init',
            [$this->sidebar, 'register']
        )->dispatch();
    }
}
