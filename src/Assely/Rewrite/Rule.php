<?php

namespace Assely\Rewrite;

class Rule
{
    /**
     * Default regrex for rule parameters.
     *
     * @var string
     */
    const DEFAULT_REGREX = '([^/]+)';

    /**
     * Rule regrex.
     *
     * @var string
     */
    protected $regrex = '';

    /**
     * Rule guid URI path.
     *
     * @var string
     */
    protected $guid = 'index.php';

    /**
     * Rule parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Resolves rule pattern, regrex and parameters.
     *
     * @param  string $pattern
     * @param  array $conditions
     *
     * @return self
     */
    public function resolve($pattern, array $conditions)
    {
        $this
            ->setRegrex($pattern)
            ->extractParameters($pattern, $conditions)
            ->replaceMocksWithConditions()
            ->generateGuid();

        return $this;
    }

    /**
     * Adds rule.
     *
     * @return void
     */
    protected function add()
    {
        return add_rewrite_rule("{$this->regrex}/?$", $this->guid);
    }

    /**
     * Extracts parameters from rule pattern and conditions.
     *
     * @param  string $pattern
     * @param  array $conditions
     *
     * @return self
     */
    public function extractParameters($pattern, array $conditions)
    {
        preg_match_all('/{(\w+)}/', $pattern, $matches, PREG_PATTERN_ORDER);

        array_map(function ($parameter) use ($conditions) {
            if (isset($conditions[$parameter])) {
                return $this->parameters[$parameter] = $conditions[$parameter];
            }

            return $this->parameters[$parameter] = self::DEFAULT_REGREX;
        }, $matches[1]);

        return $this;
    }

    /**
     * Replaces mocked pararameters with conditions in regrex string.
     *
     * @return self
     */
    public function replaceMocksWithConditions()
    {
        foreach ($this->parameters as $parameter => $condition) {
            $this->regrex = preg_replace("/{{$parameter}}/", $condition, $this->regrex);
        }

        return $this;
    }

    /**
     * Generates guid URI path.
     *
     * @return self
     */
    public function generateGuid()
    {
        $index = 1;

        foreach ($this->parameters as $parameter => $condition) {
            $separator = ($index > 1) ? '&' : '?';

            $this->guid .= "{$separator}{$parameter}=\$matches[{$index}]";

            $index++;
        }

        return $this;
    }

    /**
     * Gets the value of regrex.
     *
     * @return string
     */
    public function getRegrex()
    {
        return $this->regrex;
    }

    /**
     * Sets the value of regrex.
     *
     * @param string $regrex the regrex
     *
     * @return self
     */
    protected function setRegrex($regrex)
    {
        $this->regrex = $regrex;

        return $this;
    }

    /**
     * Gets the value of parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the value of parameters.
     *
     * @param array $parameters the parameters
     *
     * @return self
     */
    protected function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }
}
