<?php

namespace Assely\Repository;

use Assely\Contracts\Repository\RepositoryInterface;
use Assely\Contracts\Singularity\Model\ModelInterface;
use Illuminate\Contracts\Container\Container;

abstract class Repository implements RepositoryInterface
{
    /**
     * Repository model.
     *
     * @var \Assely\Contracts\Singularity\Model\ModelInterface
     */
    protected $model;

    /**
     * Repository singularity.
     *
     * @var mixed
     */
    protected $singularity;

    /**
     * Repository compatibilites.
     *
     * @var boolean
     */
    protected $compatibility = true;

    /**
     * Application.
     *
     * @var \Assely\Foundation\Application
     */
    protected $container;

    /**
     * Make repository.
     *
     * @return self
     */
    public function make(Container $container)
    {
        $this->container = $container;

        $this->container->call([$this, 'dispatch']);

        $this->register();

        return $this;
    }

    /**
     * Checks if method exists.
     *
     * @param  string $method
     *
     * @return boolean
     */
    public function methodExist($method)
    {
        return method_exists($this, $method);
    }

    /**
     * Resolve repository holders.
     *
     * @return array
     */
    public function resolveHolders()
    {
        return array_map(function ($holder) {
            return $this->resolveHolder($holder);
        }, $this->belongsTo());
    }

    /**
     * Resolve repository holders.
     *
     * @return array
     */
    public function resolveHoldersSlugs()
    {
        return array_map(function ($holder) {
            return $this->resolveHolder($holder)->slug;
        }, $this->belongsTo());
    }

    /**
     * Resolve holder.
     *
     * @param  mixed $holder
     *
     * @return mixed|string
     */
    public function resolveHolder($holder)
    {
        if (class_exists($holder)) {
            return $this->container->make($holder);
        }

        return $holder;
    }

    /**
     * Get repository argumetns.
     *
     * @return array
     */
    public function belongsTo()
    {
        return [];
    }

    /**
     * Get repository argumetns.
     *
     * @return array
     */
    public function arguments()
    {
        return [];
    }

    /**
     * Get repository columns.
     *
     * @return array
     */
    public function columns()
    {
        return [];
    }

    /**
     * Get repository templates.
     *
     * @return array
     */
    public function templates()
    {
        return [];
    }

    /**
     * Gets singularity fields.
     *
     * @return \Assely\Field\FieldsCollection
     */
    public function getFields()
    {
        return $this->singularity->getFields();
    }

    /**
     * Gets the Repository compatibility.
     *
     * @return boolean
     */
    public function getCompatibility()
    {
        return $this->compatibility;
    }

    /**
     * Gets the Repository model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the Repository model.
     *
     * @param ModelInterface $model the model
     *
     * @return self
     */
    public function setModel(ModelInterface $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the Repository singularity.
     *
     * @return mixed
     */
    public function getSingularity()
    {
        return $this->singularity;
    }

    /**
     * Sets the Repository singularity.
     *
     * @param mixed $singularity the singularity
     *
     * @return self
     */
    public function setSingularity($singularity)
    {
        $this->singularity = $singularity;

        return $this;
    }

    /**
     * Bypass repository calls to the model.
     *
     * @param  string $method
     * @param  array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }
}
