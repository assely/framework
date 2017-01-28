<?php

namespace Assely\Routing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Container\Container;

class Router
{
    /**
     * Routes collection.
     *
     * @var \Assely\Routing\RoutesCollection
     */
    protected $routes;

    /**
     * Wordpress conditions.
     *
     * @var \Assely\Routing\WordpressConditions
     */
    protected $conditions;

    /**
     * Request.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Router namspace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Construct Router.
     *
     * @param \Assely\Routing\RoutesCollection $routes
     * @param \Assely\Routing\WordpressConditions $conditions
     * @param \Illuminate\Http\Response $response
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(
        RoutesCollection $routes,
        WordpressConditions $conditions,
        Response $response,
        Container $container
    ) {
        $this->routes = $routes;
        $this->conditions = $conditions;
        $this->response = $response;
        $this->container = $container;
    }

    /**
     * Create GET route.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function get($path, $action)
    {
        return $this->addRoute(['GET'], $path, $action);
    }

    /**
     * Create HEAD route.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function head($path, $action)
    {
        return $this->addRoute(['HEAD'], $path, $action);
    }

    /**
     * Create POST route.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function post($path, $action)
    {
        return $this->addRoute(['POST'], $path, $action);
    }

    /**
     * Create PUT route.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function put($path, $action)
    {
        return $this->addRoute(['PUT'], $path, $action);
    }

    /**
     * Create DELETE route.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function delete($path, $action)
    {
        return $this->addRoute(['DELETE'], $path, $action);
    }

    /**
     * Create route that match any request method.
     *
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function any($path, $action)
    {
        return $this->addRoute(['GET', 'HEAD', 'POST', 'PUT', 'DELETE'], $path, $action);
    }

    /**
     * Create route that match specifed request method.
     *
     * @param  array $methods
     * @param  string $path
     * @param  string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function match(array $methods, $path, $action)
    {
        $methods = $this->normalizeRequestMethods($methods);

        return $this->addRoute($methods, $path, $action);
    }

    /**
     * Create route and store it in routes collection.
     *
     * @param array $method
     * @param string $path
     * @param string|callable $action
     *
     * @return \Assely\Routing\Route
     */
    public function addRoute($method, $path, $action)
    {
        $route = $this->container->make(Route::class)
            ->setMethods($method)
            ->setPath($path)
            ->setAction($action);

        return $this->routes->add($route);
    }

    /**
     * Execute router, match and resolve route.
     *
     * @return mixed
     */
    public function execute()
    {
        // Global WP and Query instance for picking
        // current request and query details.
        global $wp;
        global $wp_query;

        // If error occurs, do not search for any route.
        // Immediately process to the `404` route.
        if ($this->conditions->is('404')) {
            return $this->prepareResponse(
                $this->resolveNotFoundRoute(),
                Response::HTTP_NOT_FOUND
            );
        }

        foreach ($this->routes->getGroup($_SERVER['REQUEST_METHOD']) as $route) {
            // First we check if path matches any path
            // pattern or wordpress condition.
            if ($route->matches($wp->request, array_filter($wp_query->query_vars))) {
                // We find a match so return and break from the loop.
                return $this->prepareResponse($route->run());
            }
        }

        throw new RoutingException("Route not found. Couldn't find any matching route.");
    }

    /**
     * Resolve 404 route or redirect if not exist.
     *
     * @return mixed
     */
    public function resolveNotFoundRoute()
    {
        // Try to resolve 404 route. If such route don't
        // exist just redirect to the homepage.
        try {
            if ($notFoundRoute = $this->routes->get('404')) {
                return $notFoundRoute->run();
            }
        } catch (RoutingException $e) {
            if (wp_redirect(home_url())) {
                exit();
            }
        }
    }

    /**
     * Prepares router response.
     *
     * @param mixed $content
     *
     * @return void
     */
    public function prepareResponse($content, $status = null)
    {
        $this->response->setContent($content);

        if (isset($status)) {
            $this->response->setStatusCode($status);
        }

        return $this->response->send();
    }

    /**
     * Normalize request methods to uppercase.
     *
     * @param  string|array $methods
     *
     * @return string|array
     */
    public function normalizeRequestMethods($methods)
    {
        if (is_string($methods)) {
            return strtoupper($methods);
        }

        foreach ($methods as $key => $method) {
            $methods[$key] = strtoupper($method);
        }

        return $methods;
    }

    /**
     * Sets the value of namespace.
     *
     * @param mixed $namespace the namespace
     *
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Gets the Router namspace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
