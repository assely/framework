<?php

namespace Assely\Routing;

class RoutesCollection
{
    /**
     * Routes collection mapped
     * to requests methods.
     *
     * @var array
     */
    public static $routes = [
        'GET' => [],
        'HEAD' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    /**
     * Collection of all routes no
     * gruped by request methods.
     *
     * @var array
     */
    public static $allRoutes = [];

    /**
     * Add route to the collection.
     *
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->store($route);

        return $route;
    }

    /**
     * Store route by his request methods.
     *
     * @param  Route  $route
     *
     * @return void
     */
    public function store(Route $route)
    {
        foreach ($route->getMethods() as $method) {
            self::$routes[$method][$route->getKey()] = $route;

            self::$allRoutes[$route->getKey()] = $route;
        }
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
        return self::$routes[$method];
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
        return self::$allRoutes[$path];
    }

    /**
     * Gets all collected routes.
     *
     * @return array
     */
    public function all()
    {
        return self::$allRoutes;
    }
}
