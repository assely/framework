<?php

namespace Assely\Routing;

class RoutesCollection
{
    /**
     * Routes collection mapped to requests methods.
     *
     * @var array
     */
    protected static $routes = [
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
    protected static $allRoutes = [];

    /**
     * Add route to the collection.
     *
     * @param Route $route
     */
    public function add(Route $route)
    {
        foreach ($route->getMethods() as $method) {
            self::$routes[$method][$route->getPath()] = $route;

            self::$allRoutes[$route->getPath()] = $route;
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
        if (isset(self::$allRoutes[$path])) {
            return self::$allRoutes[$path];
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
        return self::$allRoutes;
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
        if (isset(self::$routes[$method])) {
            return self::$routes[$method];
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
        return self::$routes;
    }
}
