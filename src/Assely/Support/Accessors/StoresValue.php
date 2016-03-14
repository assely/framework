<?php

namespace Assely\Support\Accessors;

trait StoresValue
{
    /**
     * Value of value.
     *
     * @var string
     */
    protected $value;

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of value.
     *
     * @param mixed $value the value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
