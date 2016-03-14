<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\Menu;
use Assely\Singularity\Model;

class MenuModel extends Model
{
    /**
     * Default menu params.
     *
     * @var array
     */
    protected $defaults = [
        'title' => [],
    ];

    /**
     * Get menu items.
     *
     * @param array $arguments
     *
     * @return array
     */
    public function items($arguments = [])
    {
        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(Menu::class)
            ->plugIn(wp_get_nav_menu_items($this->getNavigation()->name, $arguments))
            ->getConnected();
    }

    /**
     * Has menu items?
     *
     * @return boolean
     */
    public function hasNavigation()
    {
        return has_nav_menu($this->getSlug());
    }

    /**
     * Get navigation assigned to the menu.
     *
     * @return \WP_Term
     */
    public function getNavigation()
    {
        $locations = get_nav_menu_locations();

        if (isset($locations[$this->getSlug()])) {
            $location = $locations[$this->getSlug()];

            $navigation = wp_get_nav_menus(['include' => $location]);

            return reset($navigation);
        }
    }
}
