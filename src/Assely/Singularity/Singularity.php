<?php

namespace Assely\Singularity;

use Assely\Contracts\Singularity\Model\ModelInterface;

abstract class Singularity
{
    /**
     * Postype model.
     *
     * @var Assely\Contracts\Singularity\Model\ModelInterface
     */
    protected $model;

    /**
     * Bootstrap singularity.
     *
     * @return void
     */
    public function boot()
    {
        $this->manager->boot($this);

        return $this;
    }

    /**
     * Prepare singularity values
     * and dispatch to the view.
     *
     * @param integer $id
     *
     * @return void
     */
    public function prepare($id)
    {
        // Prepare singularity only if
        // we are on the valid screen.
        if ($this->isValidScreen()) {
            // Get model metadata values.
            $collection = $this->getModel()->getMeta($id);

            // Set model metadata as singularity values.
            $this->setValue($collection->toArray());

            // Get singularity fields and boost
            // its schema with meta values.
            $this->getFields()->boostSchemaWithValues($this->getValue());

            // Dispach singularity to the view.
            $this->manager->dispatch();
        }
    }

    /**
     * Save singularity.
     *
     * @param integer $id
     *
     * @return void
     */
    public function save($id)
    {
        if ($this->requestNotEmpty()) {
            // Set fields values and propagate it's schema with this values.
            $this->getFields()->setValues($this->getRequestInput())->propagateSchemaWithValues();

            // Save sanitized field values with model.
            $this->getModel()->save($id, $this->getFields()->getSanitizedValues());
        }
    }

    /**
     * Check if request have singularity data.
     *
     * @return boolean
     */
    public function requestNotEmpty()
    {
        return  ! empty($this->getRequestInput());
    }

    /**
     * Gets value of field request.
     *
     * @return array
     */
    public function getRequestInput()
    {
        if (isset($_REQUEST[$this->getModel()->getSlug()])) {
            return $_REQUEST[$this->getModel()->getSlug()];
        }
    }

    /**
     * Gets the singularity model.
     *
     * @return \Assely\Contracts\Singularity\Model\ModelInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the singularity model.
     *
     * @param \Assely\Contracts\Singularity\Model\ModelInterface $model
     *
     * @return self
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;

        return $this;
    }
}
