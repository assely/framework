<?php

namespace Assely\Routing;

use Assely\Hook\HookFactory;
use Assely\Routing\Router;
use Closure;
use Illuminate\Contracts\Container\Container;

class Route
{
    /**
     * Route request methods.
     *
     * @var array
     */
    protected $methods;

    /**
     * Route Wordpress condition.
     *
     * @var string
     */
    protected $condition;

    /**
     * Route raw condition including filter.
     *
     * @var string
     */
    protected $key;

    /**
     * Route action callback.
     *
     * @var string|callable
     */
    protected $action;

    /**
     * Route filter rules.
     *
     * @var string
     */
    protected $filter;

    /**
     * Route pattern matcher.
     *
     * @var string
     */
    protected $pattern;

    /**
     * Route guid path.
     *
     * @var string
     */
    protected $guid;

    /**
     * Route parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Route query.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Route parameters rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Route controller.
     *
     * @var \Assely\Routing\Controller
     */
    protected $controller;

    /**
     * Hook factory.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Router instance.
     *
     * @var \Assely\Routing\Router
     */
    protected $router;

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
     * Check if route matches path.
     *
     * @param  string $pattern
     *
     * @return boolean
     */
    public function matchesPathPattern($path)
    {
        if (isset($this->pattern)) {
            return is_string($path) && preg_match("@^{$this->pattern}/?$@", $path);
        }
    }

    /**
     * Check if route maching Wordpress conditions.
     *
     * @param  array $conditions
     *
     * @return boolean
     */
    public function matchesWordpressConditions($conditions)
    {
        $condition = $conditions->{$this->getCondition()};

        return isset($condition) && call_user_func_array($condition, [$this->getFilter()]);
    }

    /**
     * Resolve route.
     *
     * @return mixed
     */
    public function resolve()
    {
        // If action is callable we
        // are done. Just call it.
        if ($this->action instanceof Closure) {
            return $this->callAction();
        }

        // Lets try resolve route controller.
        return $this->resolveController();
    }

    /**
     * Set route parameters rules.
     *
     * @param  array $rules
     *
     * @return void
     */
    public function where(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Set route query.
     *
     * @param array $query
     *
     * @return self
     */
    public function queries(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Add route rewrite rules
     * to the Wordpress.
     *
     * @return void
     */
    public function hookRewriteRules()
    {
        $this->resolvePath();

        $this->hook->action('init', [$this, 'addRewriteRules'])->dispatch();
    }

    /**
     * Add route rewrite rules.
     *
     * @return void
     */
    public function addRewriteRules()
    {
        // Register tag rewrite rule
        // for route parameters.
        foreach ($this->getParameters() as $name => $parameter) {
            add_rewrite_tag("%{$name}%", $parameter);
        }

        // Add route rewrite rule.
        add_rewrite_rule("{$this->pattern}/?$", $this->getGuid(), 'top');
    }

    /**
     * Call controller method assigned to route
     *
     * @throws RoutingException
     *
     * @return mixed
     */
    public function resolveController()
    {
        // Split controller annotation to exctract
        // controller name and method to call.
        list($controller, $method) = explode('@', $this->getAction());

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
            [$this->getController(), $method],
            $this->getParametersValues()
        );
    }

    /**
     * Call callable router action
     *
     * @return mixed
     */
    public function callAction()
    {
        return $this->container->call(
            $this->getAction(),
            $this->getParametersValues()
        );
    }

    /**
     * Resolve route path.
     *
     * @return void
     */
    public function resolvePath()
    {
        // Resolve parameters and make map where key
        // is parameter name and value is pattern.
        $this->resolveParameters();

        // Resolve route pattern. Replace mocked
        // path with parameters patterns.
        $this->generatePattern($this->getCondition());

        // Generate raw unpermalinked path
        // required for rewrite rule.
        $this->generateGuid();
    }

    /**
     * Resolve route path pattern.
     *
     * @return string
     */
    public function generatePattern($path)
    {
        // Replace each parameter mock in the
        // path with his validation schema.
        foreach ($this->getParameters() as $key => $schema) {
            $path = preg_replace("/\\{({$key})\\}/", $schema, $path);
        }

        $this->setPattern($path);
    }

    /**
     * Gets the Route pattern matcher.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets the Route pattern path.
     *
     * @param string $pattern the pattern
     *
     * @return self
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Generate raw, unpermalinked path.
     *
     * @return string
     */
    public function generateGuid($path = 'index.php?')
    {
        $index = 1;

        foreach ($this->getQuery() as $query => $value) {
            $path .= "{$query}={$value}";
        }

        foreach ($this->getParameters() as $parameter => $pattern) {
            $separator = ($index > 1 || isset($this->query)) ? '&' : '';

            $path .= "{$separator}{$parameter}=\$matches[{$index}]";

            $index++;
        }

        return $this->setGuid($path);
    }

    /**
     * Gets the Route raw path.
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Sets the Route guid path.
     *
     * @param string $guid the guid
     *
     * @return self
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Resolve parameters from route condition.
     * Maps parameters names to his pattern rules.
     *
     * @return array
     */
    public function resolveParameters()
    {
        // Find all parameters mocked with brackets.
        preg_match_all('/\\{(.*?)\\}/', $this->getCondition(), $matches);

        // Map each found match to collection. If parameter pattern
        // is not set use default "everythig between slashes".
        foreach ($matches[1] as $index => $name) {
            $pattern = ($this->getRule($name)) ?: '([^/]+)';

            $this->setParameter($name, $pattern);
        }
    }

    /**
     * Gets the Route parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the Route parameters.
     *
     * @param array $parameters the parameters
     *
     * @return self
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Sets the Route parameter.
     *
     * @param array $parameter
     *
     * @return self
     */
    public function setParameter($parameter, $value)
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }

    /**
     * Get route parameters values.
     *
     * @return array
     */
    public function getParametersValues()
    {
        $parameters = [];

        foreach ($this->parameters as $name => $pattern) {
            $parameters[$name] = get_query_var($name);
        }

        return $parameters;
    }

    /**
     * Gets the Route parameters rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Sets the Route parameter.
     *
     * @param array $parameter
     *
     * @return self
     */
    public function getRule($name)
    {
        return isset($this->rules[$name]) ? $this->rules[$name] : null;
    }

    /**
     * Sets the Route parameters rules.
     *
     * @param array $rules the rules
     *
     * @return self
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Gets the Route query.
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets the Route query.
     *
     * @param array $query the query
     *
     * @return self
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
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
     * @param mixed $methods the methods
     *
     * @return self
     */
    public function setMethods($methods)
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
     * @param mixed $condition the condition
     *
     * @return self
     */
    public function setCondition($condition)
    {
        // Split condition annotation to exctract
        // wordpress condition and route filter.
        $road = explode(':', $condition);

        // First part of the road is condition.
        $this->condition = $this->normalizeCondition(reset($road));

        // Save condition before split. It will be
        // used as key in routes collection.
        $this->setKey($condition);

        // Set filter if road lenght is more than one.
        // Filter definition is last part of the road.
        if (count($road) > 1) {
            $this->setFilter(trim(end($road), '{}'));
        }

        return $this;
    }

    /**
     * Normalize route condition. Remove unnecessary backslashes.
     *
     * @return string
     */
    public function normalizeCondition($condition)
    {
        if ($condition[0] == '/') {
            $condition = substr($condition, 1);
        }

        return $condition;
    }

    /**
     * Gets the value of action.
     *
     * @return mixed
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
     * Gets the value of filter.
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Sets the Route filter rules.
     *
     * @param string $filter the filter
     *
     * @return self
     */
    public function setFilter($filter)
    {
        $this->filter = ( ! strpos($filter, ',')) ? $filter : explode(',', $filter);

        return $this;
    }

    /**
     * Gets the Route raw condition including filter.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the Route raw condition including filter.
     *
     * @param string $key the key
     *
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
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
    protected function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }
}
