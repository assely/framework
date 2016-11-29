<?php

namespace Assely\Menu;

use Assely\Cache\Cache;
use Assely\Contracts\Singularity\Model\ModelInterface;
use Assely\Support\Accessors\UsesFingerprint;

class Menu
{
    use UsesFingerprint;

    /**
     * Menu manager.
     *
     * @var \Assely\Menu\MenuManager
     */
    protected $manager;

    /**
     * Menu model.
     *
     * @var \Assely\Contracts\Singularity\Model\ModelInterface
     */
    protected $model;

    /**
     * Cache factory.
     *
     * @var Assely\Cache\Cache
     */
    protected $cache;

    /**
     * Construct menu.
     *
     * @param \Assely\Menu\MenuManager $manager
     * @param string $slug
     * @param array $arguments
     */
    public function __construct(
        MenuManager $manager,
        Cache $cache
    ) {
        $this->manager = $manager;
        $this->cache = $cache;

        $this->manager->boot($this);
    }

    /**
     * Register menu.
     *
     * @return void
     */
    public function register()
    {
        $this->setFingerprint($this->generateFingerprint());

        return register_nav_menus([$this->model->getSlug() => $this->model->getSingular()]);
    }

    /**
     * Deregister menu.
     *
     * @return void
     */
    public function deregister()
    {
        return unregister_nav_menu($this->model->getSlug());
    }

    /**
     * Gets navigation object assigned to this menu.
     *
     * @return object|false
     */
    public function nav()
    {
        return $this->getModel()->getNavigation();
    }

    /**
     * Get menu items.
     *
     * @return array
     */
    public function items()
    {
        // Transform flat menu structure to the tree.
        // For better performance - cache results.
        if (! $this->cache->get($this->getFingerprint())) {
            $items = $this->generateTree($this->model->items());

            $this->cache->put($this->getFingerprint(), $items);

            return $items;
        }

        // We already have generated menu tree. Get it form the cache.
        return $this->cache->get($this->getFingerprint());
    }

    /**
     * Clear cache.
     *
     * @return void
     */
    public function clearCache()
    {
        $this->cache->flush($this->getFingerprint());
    }

    /**
     * Transform flat menu structure into tree.
     *
     * @param \Illuminate\Support\Collection $items
     *
     * @return \Illuminate\Support\Collection
     */
    public function generateTree($items)
    {
        foreach ($items->reverse()->all() as $index => $item) {
            $parent = $this->findParent($items, $item);

            if (isset($parent)) {
                $parent->setChild($item);

                $items->forget($index);
            }
        }

        return $items;
    }

    /**
     * Find parent item in menu.
     *
     * @param \Illuminate\Support\Collection &$items
     * @param \Assely\Adapter\Menu $item
     *
     * @return \Illuminate\Support\Collection
     */
    public function findParent(&$items, $item)
    {
        return $items->filter(function ($element) use ($item) {
            return $item->parent_id == $element->id;
        })->first();
    }

    /**
     * Has menu items?
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->model->hasNavigation();
    }

    /**
     * Generate fingerprint used as cache key.
     *
     * @return string
     */
    public function generateFingerprint()
    {
        $namespace = get_class($this);

        return "{$namespace}/{$this->getSlug()}";
    }

    /**
     * Get menu slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->model->getSlug();
    }

    /**
     * Gets the Menu model.
     *
     * @return \Assely\Contracts\Singularity\Model\ModelInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the Menu model.
     *
     * @param \Assely\Contracts\Singularity\Model\ModelInterface $model the model
     *
     * @return self
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;

        return $this;
    }
}
