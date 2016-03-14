<?php

namespace Assely\Foundation;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;

abstract class Depot
{
    /**
     * Depot items collection.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $collection;

    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Construct depot.
     *
     * @param \Illuminate\Contracts\Config\Repository $collection
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(
        Repository $collection,
        Container $container
    ) {
        $this->collection = $collection;
        $this->container = $container;
    }

    /**
     * Get item form the collection.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function reach($name)
    {
        if ($this->collection->has($name)) {
            return $this->collection->get($name);
        }
    }

    /**
     * Add item to the collection.
     *
     * @param mixed $item
     *
     * @return mixed
     */
    public function hang($item)
    {
        $this->collection->set($item->getSlug(), $item);

        return $item;
    }
}
