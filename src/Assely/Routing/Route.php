<?php

namespace Assely\Routing;

use Assely\Hook\HookFactory;
use Assely\Contracts\Routing\RouteInterface;
use Illuminate\Contracts\Container\Container;

class Route implements RouteInterface
{
    use ControllerActionResolver;

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
     * @return bool
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
     * @return bool
     */
    public function matchesWordpressConditions($conditions)
    {
        $condition = $conditions->{$this->getCondition()};

        return isset($condition) && call_user_func_array($condition, [$this->getFilter()]);
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
        $this->filter = (! strpos($filter, ',')) ? $filter : explode(',', $filter);

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
}
