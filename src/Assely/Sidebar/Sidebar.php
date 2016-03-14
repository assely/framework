<?php

namespace Assely\Sidebar;

use Assely\Support\Accessors\HasArguments;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;

class Sidebar
{
    use HasSlug, HasArguments, HasTitles;

    /**
     * Default widget area arguments
     *
     * @var array
     */
    private $defaults = [
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',

        'title' => [],
        'description' => '',
    ];

    /**
     * Sidebar manager.
     *
     * @var \Assely\Sidebar\SidebarManager
     */
    private $manager;

    /**
     * Construnct sidebar.
     *
     * @param \Assely\Sidebar\SidebarManager $manager
     */
    public function __construct(SidebarManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Bootstrap sidebar.
     *
     * @return void
     */
    public function boot()
    {
        $this->setSingular($this->getArgument('title'));
        $this->setPlural($this->getArgument('title'));

        $this->manager->boot($this);
    }

    /**
     * Register widget area.
     *
     * @return integer
     */
    public function register()
    {
        $parameters = [
            'name' => $this->getSingular(),
            'id' => $this->getSlug(),
        ];

        return register_sidebar(array_merge($this->getArguments(), $parameters));
    }

    /**
     * Gets sidebar.
     *
     * @return void
     */
    public function render()
    {
        dynamic_sidebar($this->getSlug());
    }

    /**
     * Check if sidebar has widgets.
     *
     * @return boolean
     */
    public function hasWidgets()
    {
        return is_active_sidebar($this->getSlug());
    }
}
