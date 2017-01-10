<?php

namespace Assely\Routing;

use Assely\Contracts\Routing\RouteInterface;
use Assely\Hook\HookFactory;
use Assely\Routing\ControllerActionResolver;
use Illuminate\Contracts\Container\Container;

class Route
{
    use ControllerActionResolver;

    /**
     * Route request methods.
     *
     * @var array
     */
    protected $methods;

    /**
     * Route condition.
     *
     * @var string
     */
    protected $condition;

    /**
     * Route action callback.
     *
     * @var string|callable
     */
    protected $action;

    /**
     * Route query arguments.
     *
     * @var array|null
     */
    protected $queries;

    /**
     * Construct route.
     *
     * @param \Assely\Routing\Router $router
     * @param \Assely\Hook\HookFactory $hook
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(
        Router $router,
        HookFactory $hook,
        Container $container
    ) {
        $this->router = $router;
        $this->hook = $hook;
        $this->container = $container;
    }

    /**
     * Checks if route matches request.
     *
     * @param  string $pattern
     *
     * @return bool
     */
    public function matches($request, $queries)
    {
        $this->setQueries($queries);

        if (! $request && $this->isHomeCondition()) {
            return true;
        }

        $schema = $this->getConditionMock($queries);

        return preg_match("@^{$schema}$@", $request);
    }

    /**
     * Gets condition mocked with queries values.
     *
     * @param  array $queries
     *
     * @return array
     */
    public function getConditionMock(array $queries)
    {
        $condition = $this->getCondition();

        foreach ($queries as $query => $value) {
            $condition = preg_replace("/\\{({$query})\\}/", $value, $condition);
        };

        return $condition;
    }

    /**
     * Checks if this route condition is home.
     *
     * @return boolean
     */
    public function isHomeCondition()
    {
        return $this->getCondition() === '/';
    }

    /**
     * Gets the value of methods.
     *
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Sets the value of methods.
     *
     * @param array $methods the methods
     *
     * @return self
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Gets the value of condition.
     *
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Sets the value of condition.
     *
     * @param string $condition the condition
     *
     * @return self
     */
    public function setCondition($condition)
    {
        $this->condition = trim($condition, '/');

        return $this;
    }

    /**
     * Gets the value of methods.
     *
     * @return string|callable
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Gets the value of queries.
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Sets the value of queries.
     *
     * @param array $queries the queries
     *
     * @return self
     */
    public function setQueries(array $queries)
    {
        $this->queries = $queries;

        return $this;
    }
}
