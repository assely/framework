<?php

namespace Assely\Thumbnail;

use Assely\Hook\HookFactory;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Assely\Support\Accessors\HasArguments;

class Thumbnail
{
    use HasSlug, HasArguments, HasTitles;

    /**
     * Default asset arguments.
     *
     * @var array
     */
    private $defaults = [
        'size' => [800, 600],
        'crop' => false,
        'title' => [],
    ];

    /**
     * Construct thumbnail.
     *
     * @param \Assely\Hook\HookFactory $hook
     * @param string $slug
     * @param array $arguments
     */
    public function __construct(HookFactory $hook)
    {
        $this->hook = $hook;
    }

    /**
     * Dispach thumbnail to registration.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->setSingular($this->getArgument('title'));
        $this->setPlural($this->getArgument('title'));

        $this->hook->action(
            'after_setup_theme',
            [$this, 'register']
        )->dispatch();

        $this->hook->filter(
            'image_size_names_choose',
            [$this, 'registerName']
        )->dispatch();
    }

    /**
     * Register thumbnail size.
     *
     * @return void
     */
    public function register()
    {
        return add_image_size(
            $this->getSlug(),
            $this->getArgument('size')[0],
            $this->getArgument('size')[1],
            $this->getArgument('crop')
        );
    }

    /**
     * Remove thumbnail.
     *
     * @return void
     */
    public function remove()
    {
        return remove_image_size($this->getSlug());
    }

    /**
     * Makes thumbnail as default
     * post featured image size.
     *
     * @return void
     */
    public function makeAsDefault()
    {
        return set_post_thumbnail_size(
            $this->getArgument('size')[0],
            $this->getArgument('size')[1],
            $this->getArgument('crop')
        );
    }

    /**
     * Register thumbnail name.
     *
     * @param  array $names
     * @return array
     */
    public function registerName($names)
    {
        return array_merge($names, [
            $this->getSlug() => $this->getSingular(),
        ]);
    }
}
