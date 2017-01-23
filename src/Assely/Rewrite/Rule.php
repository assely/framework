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
     * @var string
     */
    protected $regrex = '';

    /**
     * @var string
     */
    protected $guid = 'index.php';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param $pattern
     */
    public function resolve($pattern, $conditions)
    {
        $this
            ->setRegrex($pattern)
            ->extractParameters($pattern, $conditions)
            ->replaceMocksWithConditions()
            ->generateGuid();

        return $this;
    }

    public function add()
    {
        return add_rewrite_rule("{$this->regrex}/?$", $this->guid);
    }

    /**
     * @param $pattern
     */
    public function extractParameters($pattern, $conditions)
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
     * @param $conditions
     */
    public function replaceMocksWithConditions()
    {
        foreach ($this->parameters as $parameter => $condition) {
            $this->regrex = preg_replace("/{{$parameter}}/", $condition, $this->regrex);
        }

        return $this;
    }

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
