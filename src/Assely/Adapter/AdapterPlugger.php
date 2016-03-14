<?php

namespace Assely\Adapter;

use Assely\Contracts\Singularity\Model\ModelInterface;
use Illuminate\Support\Collection;

class AdapterPlugger
{
    /**
     * Collection of connected adapters.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Adapter object.
     *
     * @var string
     */
    protected $adapter;

    /**
     * Adapter model.
     *
     * @var \Assely\Contracts\Singularity\Model\ModelInterface
     */
    protected $model;

    /**
     * Construct adapters plugger.
     *
     * @param \Illuminate\Support\Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Plugin adaptees.
     *
     * @return void
     */
    public function plugIn($adaptee)
    {
        if (is_array($adaptee)) {
            foreach ($adaptee as $item) {
                $this->plugIn($item);
            }

            return $this;
        }

        $this->collection->push($this->connectAdapter($adaptee));

        return $this;
    }

    /**
     * Connect adapter, model and adaptee.
     *
     * @param string $adaptee
     *
     * @return \Assely\Contracts\Adapter\AdapterInterface
     */
    public function connectAdapter($adaptee)
    {
        $adapter = new $this->adapter;

        $adapter
            ->setAdaptee($adaptee)
            ->setModel($this->model)
            ->connect();

        return $adapter;
    }

    /**
     * Gets connected adapters.
     *
     * @return array
     */
    public function getConnected()
    {
        return $this->collection;
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
     * Sets the Adapter object.
     *
     * @param string $adapter the adapter
     *
     * @return self
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }
}
