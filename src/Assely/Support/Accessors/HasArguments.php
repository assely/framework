<?php

namespace Assely\Support\Accessors;

use Illuminate\Support\Arr;

trait HasArguments
{
    /**
     * Arguments array.
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * Gets the value of arguments.
     *
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Sets the value of arguments.
     *
     * @param mixed $arguments the arguments
     *
     * @return self
     */
    public function setArguments($arguments)
    {
        $parameters = array_merge($this->getDefaults(), $this->arguments);

        $this->arguments = array_merge($parameters, $arguments);

        return $this;
    }

    /**
     * Gets the value of argument.
     *
     * @return mixed
     */
    public function getArgument($argument)
    {
        return Arr::get($this->arguments, $argument);
    }

    /**
     * Sets the value of argument.
     *
     * @param string $argument
     * @param mixed $value
     *
     * @return self
     */
    public function setArgument($argument, $value)
    {
        Arr::set($this->arguments, $argument, $value);

        return $this;
    }

    /**
     * Gets the value of defaults arguments.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Sets the value of defaults arguments.
     *
     * @param array $defaults the defaults
     *
     * @return self
     */
    protected function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * Gets the value of default argument.
     *
     * @return mixed
     */
    public function getDefault($argument)
    {
        return Arr::get($this->defaults, $argument);
    }

    /**
     * Sets the value of default argument.
     *
     * @param string $argument
     * @param mixed $value
     *
     * @return self
     */
    public function setDefault($argument, $value)
    {
        Arr::set($this->defaults, $argument, $value);

        return $this;
    }
}
