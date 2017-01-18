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

        // The `wp_get_nav_menu_items` function do not generate
        // menu items classes. This filter helps with that.
        $this->hook->filter('wp_get_nav_menu_items', function ($items, $menu, $args) {
            _wp_menu_item_classes_by_context($items);

            return $items;
        }, ['numberOfArguments' => 3])->dispatch();
    }
}
