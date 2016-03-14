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
     * Gets the value of arguments.
     *
     * @return mixed
     */
    public function getArgument($propety)
    {
        return Arr::get($this->arguments, $propety);
    }

    /**
     * Sets the value of arguments.
     *
     * @param mixed $arguments the arguments
     *
     * @return self
     */
    public function setArgument($propety, $value)
    {
        Arr::set($this->arguments, $propety, $value);

        return $this;
    }

    /**
     * Gets the Default post type arguments.
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Sets the value of defaults.
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
}
