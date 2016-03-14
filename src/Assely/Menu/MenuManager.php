<?php

namespace Assely\Menu;

use Assely\Singularity\Manager;

class MenuManager extends Manager
{
    /**
     * Bootstrap menu.
     *
     * @param \Assely\Menu\Menu $assistant
     *
     * @return void
     */
    public function boot($menu)
    {
        $this->menu = $menu;

        $this->hooks();
    }

    /**
     * Setup hooks.
     *
     * @return void
     */
    public function hooks()
    {
        // Register menu when theme is ready.
        $this->hook->action(
            'after_setup_theme',
            [$this->menu, 'register']
        )->dispatch();

        // For better performance we are caching generated menu
        // tree, because of that we need to clear cache
        // when menu has been updated or saved.
        $this->hook->action(
            'wp_update_nav_menu',
            [$this->menu, 'clearCache']
        )->dispatch();
    }
}
