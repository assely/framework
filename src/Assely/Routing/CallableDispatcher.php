<?php

namespace Assely\Routing;

use Assely\Ajax\Ajax;
use Illuminate\Contracts\Container\Container;

class CallableDispatcher
{
    /**
     * Construct controller dispacher.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Dispatches controller method.
     *
     * @param  mixed $route
     * @param  string $controller
     * @param  string $method
     *
     * @return mixed
     */
    public function dispatch($route, $callback)
    {
        return $this->container->call(
            $callback,
            $this->getMethodArguments($route)
        );
    }

    /**
     * Gets method arguments.
     *
     * @param  mixed $route
     *
     * @return mixed
     */
    public function getMethodArguments($route)
    {
        if ($route instanceof Ajax) {
            return [];
        }

        return $route->getQueries();
    }
}
