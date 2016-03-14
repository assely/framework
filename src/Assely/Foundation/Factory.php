<?php

namespace Assely\Foundation;

use Illuminate\Contracts\Container\Container;

abstract class Factory
{
    /**
     * Assely application.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Construct factory.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
