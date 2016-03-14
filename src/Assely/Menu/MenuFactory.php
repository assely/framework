<?php

namespace Assely\Menu;

use Assely\Foundation\Depot;
use Assely\Singularity\Model\MenuModel;

class MenuFactory extends Depot
{
    /**
     * Create menu.
     *
     * @param string $slug
     * @param array $arguments
     *
     * @return \Assely\Menu\Menu
     */
    public function create($slug, array $arguments = [])
    {
        return $this->make($slug, $arguments);
    }

    /**
     * Get menu.
     *
     * @param string $slug
     * @param array $arguments
     *
     * @throws MenuException
     *
     * @return \Assely\Menu\Menu
     *
     */
    public function get($slug)
    {
        if ($menu = $this->reach($slug)) {
            return $menu;
        }

        throw new MenuException("Menu [{$slug}] not found.");
    }

    /**
     * Make and hang menu.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Menu\Menu
     */
    protected function make($slug, $arguments = [])
    {
        $menu = $this->container->make(Menu::class);
        $model = $this->container->make(MenuModel::class);

        $menu->setModel($model->make($slug, $arguments));

        return $this->hang($menu);
    }
}
