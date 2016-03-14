<?php

namespace Assely\Routing;

use Assely\Routing\Router;
use Illuminate\Contracts\Container\Container;

class ControllerDispatcher
{
    /**
     * @param Container $container
     * @param Router $router
     */
    public function __construct(
        Container $container,
        Router $router
    ) {
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * @param $route
     * @return mixed
     */
    public function dispatch($route)
    {
        if ($route->getAction() instanceof Closure) {
            return $route->callAction();
        }

        return $this->resolveController($route->getAction());
    }

    /**
     * Call controller method assigned to route
     *
     * @throws RoutingException
     *
     * @return mixed
     */
    public function resolveController($action)
    {
        // Split controller annotation to exctract
        // controller name and method to call.
        list($controller, $method) = explode('@', $action);

        // Make route controller.
        $this->setController($this->makeController($controller));

        // Call controller method if it exist.
        if ($this->controllerHasMethod($method)) {
            return $this->callControllerMethod($method);
        }

        // Controller do not have defined
        // method. Notify about this.
        $this->getController()->missingMethod($method);
    }

    /**
     * Make route controller.
     *
     * @param  string $name
     *
     * @throws \Assely\Routing\RoutingException
     *
     * @return \Assely\Routing\Controller
     *
     */
    public function makeController($name)
    {
        $class = $this->router->getNamespace() . "\\{$name}";

        if (class_exists($class)) {
            return $this->container->make($class);
        }

        throw new RoutingException("Controller [{$class}] do not exists.");
    }

    /**
     * Check if controller has defined method.
     *
     * @param  BaseController $controller
     *
     * @return boolean
     */
    public function controllerHasMethod($method)
    {
        return method_exists($this->getController(), $method);
    }

    /**
     * Call controller method.
     *
     * @param  string $method
     *
     * @return void
     */
    public function callControllerMethod($method)
    {
        return $this->container->call(
            [$this->getController(), $method]
        );
    }

    /**
     * Gets the Route controller.
     *
     * @return \Assely\Routing\Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the Route controller.
     *
     * @param \Assely\Routing\Controller $controller the controller
     *
     * @return self
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }
}
