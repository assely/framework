<?php

namespace Assely\Adapter;

use Exception;
use JsonSerializable;
use Assely\Contracts\Adapter\AdapterInterface;
use Assely\Contracts\Singularity\Model\ModelInterface;

abstract class Adapter implements AdapterInterface, JsonSerializable
{
    /**
     * Adapter adaptee.
     *
     * @var mixed
     */
    protected $adaptee;

    /**
     * Adapter model.
     *
     * @var mixed
     */
    protected $model;

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    protected $touches = [];

    /**
     * Connecting adapter to the adaptee.
     */
    abstract public function connect();

    /**
     * Dynamically get adaptee properties.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (! property_exists($this, $name) && method_exists($this, $name)) {
            return $this->{$name}();
        }

        if (array_key_exists($name, $this->touches)) {
            return $this->adaptee->{$this->touches[$name]};
        }

        throw new Exception("Property [{$name}] does not exist on this instance.");
    }

    /**
     * Dynamically set adaptee properties.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->touches)) {
            $this->adaptee->{$this->touches[$name]} = $value;
        }
    }

    /**
     * Return adapter key when casting to the string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s/%s',
            get_class($this),
            $this->id
        );
    }

    /**
     * Format date.
     *
     * @param string $date
     *
     * @return string
     */
    protected function formatDate($date)
    {
        return date_i18n(get_option('date_format'), strtotime($date));
    }

    /**
     * Reject hidden meta.
     *
     * @param array $meta
     *
     * @return array
     */
    public function rejectHiddenMeta($meta)
    {
        return $meta->filter(function ($value, $key) {

            // Hidden meta starts with "_". Get value
            // if key do not start with underscore.
            if (substr($key, 0, 1) !== '_') {
                return $value;
            }
        })->all();
    }

    /**
     * Gets the Adapter adaptee.
     *
     * @return mixed
     */
    public function getAdaptee()
    {
        return $this->adaptee;
    }

    /**
     * Sets the Adapter adaptee.
     *
     * @param mixed $adaptee the adaptee
     *
     * @return self
     */
    public function setAdaptee($adaptee)
    {
        $this->adaptee = $adaptee;

        return $this;
    }

    /**
     * Gets the Adapter model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the Adapter model.
     *
     * @param \Assely\Contracts\Singularity\Model\ModelInterface $model the model
     *
     * @return self
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Encode adapter to json.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this);
    }

    /**
     * Encode adapter to array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->JsonSerialize();
    }
}
