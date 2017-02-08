<?php

namespace Assely\Routing;

class RoutesCollection
{
    /**
     * Routes collection mapped to requests methods.
     *
     * @var array
     */
    protected $routes = [
        'GET' => [],
        'HEAD' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    /**
     * Collection of all ungrouped routes.
     *
     * @var array
     */
    protected $allRoutes = [];

    /**
     * Add route to the collection.
     *
     * @param Route $route
     */
    public function add(Route $route)
    {
        foreach ($route->getMethods() as $method) {
            $this->routes[$method][$route->getPath()] = $route;

            $this->allRoutes[$route->getPath()] = $route;
        }

        return $route;
    }

    /**
     * Get the route form collection.
     *
     * @param  string $path
     *
     * @return Assely\Routing\Route
     */
    public function get($path)
    {
        if (isset($this->allRoutes[$path])) {
            return $this->allRoutes[$path];
        }

        throw new RoutingException("Route [{$path}] does not exist.");
    }

    /**
     * Gets all collected routes.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->allRoutes;
    }

    /**
     * Get the route group form collection.
     *
     * @param  string $method
     *
     * @return Assely\Routing\Route
     */
    public function getGroup($method)
    {
        if (isset($this->routes[$method])) {
            return $this->routes[$method];
        }

        throw new RoutingException("Routes group [{$method}] does not exist.");
    }

    /**
     * Gets all grouped routes.
     *
     * @return array
     */
    public function getGroups()
    {
        return $this->routes;
    }
}
