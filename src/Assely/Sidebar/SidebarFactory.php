<?php

namespace Assely\Sidebar;

use Assely\Foundation\Depot;

class SidebarFactory extends Depot
{
    /**
     * Create sidebar.
     *
     * @param string $slug
     * @param array $arguments
     *
     * @return \Assely\Sidebar\Sidebar
     */
    public function create($slug, $arguments = [])
    {
        return $this->make($slug, $arguments);
    }

    /**
     * Get sidebar.
     *
     * @param string $slug
     *
     * @throws SidebarException
     *
     * @return \Assely\Sidebar\Sidebar
     */
    public function get($slug)
    {
        if ($sidebar = $this->reach($slug)) {
            return $sidebar;
        }

        throw new SidebarException("Sidebar [{$slug}] not found.");
    }

    /**
     * Check if sidebar is active.
     *
     * @return bool
     */
    public function isActive($slug)
    {
        return $this->get($slug)->hasWidgets();
    }

    /**
     * Make sidebar.
     *
     * @param string $slug
     * @param array $argumetns
     *
     * @return \Assely\Sidebar\Sidebar
     */
    protected function make($slug, $arguments = [])
    {
        $sidebar = $this->container->make(Sidebar::class);

        $sidebar
            ->setSlug($slug)
            ->setArguments($arguments)
            ->boot();

        return $this->hang($sidebar);
    }
}
