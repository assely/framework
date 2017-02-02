<?php

namespace Assely\Routing;

use Assely\Hook\HookFactory;
use Assely\Contracts\Routing\RouteInterface;
use Illuminate\Contracts\Container\Container;

class Route extends ActionResolver implements RouteInterface
{
    /**
     * Route request methods.
     *
     * @var array
     */
    protected $methods;

    /**
     * Route path.
     *
     * @var string
     */
    protected $path;

    /**
     * Route action callback.
     *
     * @var string|callable
     */
    protected $action;

    /**
     * Route query arguments.
     *
     * @var array
     */
    protected $queries = [];

    /**
     * Route rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Construct route.
     *
     * @param \Assely\Routing\Router $router
     * @param \Assely\Hook\HookFactory $hook
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(
        Router $router,
        WordpressConditions $conditions,
        HookFactory $hook,
        Container $container
    ) {
        $this->router = $router;
        $this->conditions = $conditions;
        $this->hook = $hook;
        $this->container = $container;
    }

    /**
     * Checks if route matches request.
     *
     * @param  string $request [description]
     * @param  array  $queries [description]
     *
     * @return bool
     */
    public function matches($request, $queries = [])
    {
        $this->setQueries($queries);

        if (! $request && $this->isHomePath()) {
            return true;
        }

        if ($matches = preg_match("@^{$this->getPathMock($queries)}$@", $request)) {
            if ($this->rulesNotPassed()) {
                return false;
            }
        }

        return (bool) $matches;
    }

    /**
     * Gets path mocked with queries values.
     *
     * @param  array $queries
     *
     * @return array
     */
    public function getPathMock(array $queries)
    {
        $path = $this->getPath();

        foreach ($queries as $query => $value) {
            if (is_array($value)) {
                $value = reset($value);
            }

            $path = preg_replace("/\\{({$query})\\}/", $value, $path);
        }

        return $path;
    }

    /**
     * Checks if this route path is home.
     *
     * @return bool
     */
    public function isHomePath()
    {
        return empty($this->getPath());
    }

    /**
     * Sets route additional matching rules.
     *
     * @param  array  $rules
     * @return self
     */
    public function where(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Evaluates statuses of rules.
     *
     * @return array
     */
    public function evaluateRules()
    {
        foreach ($this->rules as $rule => $condition) {
            $this->rules[$rule] = $this->conditions->is($rule, $condition);
        }

        return $this->rules;
    }

    /**
     * Checks if all rules passed a verification.
     *
     * @return bool
     */
    public function rulesNotPassed()
    {
        return in_array(false, $this->evaluateRules(), true);
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
     * Gets the value of path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the value of path.
     *
     * @param string $path the path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = trim($path, '/');

        return $this;
    }

    /**
     * Gets the value of action.
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

    /**
     * Gets the Route rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }
}
