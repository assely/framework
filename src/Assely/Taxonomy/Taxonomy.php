<?php

namespace Assely\Taxonomy;

use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\TaxonomyModel;

abstract class Taxonomy extends Repository
{
    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\TaxonomyModel
     */
    protected $taxonomy;

    /**
     * Repository factory.
     *
     * @var \Assely\Taxonomy\TaxonomyFactory
     */
    protected $factory;

    /**
     * Hook service.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Register repository.
     *
     * @param \Assely\Singularity\Model\TaxonomyModel $model
     * @param \Assely\Taxonomy\TaxonomyFactory $factory
     * @param \Assely\Hook\HookFactory $hook
     */
    public function dispatch(
        TaxonomyModel $taxonomy,
        TaxonomyFactory $factory,
        HookFactory $hook
    ) {
        $this->taxonomy = $taxonomy;
        $this->factory = $factory;
        $this->hook = $hook;
    }

    /**
     * Dispatch repository.
     *
     * @return void
     */
    protected function register()
    {
        // Make taxonomy model for the factory.
        $this->setModel($this->makeTaxonomyModel());

        // Build taxonomy with model and singularity holders.
        $this->setSingularity($this->makeTaxonomyInstance());

        // Set fields to the taxonomy.
        if ($this->methodExist('fields')) {
            $this->registerFields();
        }

        // Set columns to the taxonomy, after admin init.
        if ($this->methodExist('columns')) {
            $this->registerColumns();
        }
    }

    /**
     * Create taxonomy model.
     *
     * @return \Assely\Singularity\Model\TaxonomyModel
     */
    private function makeTaxonomyModel()
    {
        return $this->taxonomy->make($this->slug, $this->arguments());
    }

    /**
     * Create taxonomy singularity instance.
     *
     * @return \Assely\Taxonomy\Taxonomy
     */
    protected function makeTaxonomyInstance()
    {
        return $this->factory->create($this->getModel(), $this->resolveHoldersSlugs());
    }

    /**
     * Register singularity fields.
     *
     * @return \Assely\Taxonomy\Taxonomy
     */
    protected function registerFields()
    {
        return $this->getSingularity()->fields($this->fields());
    }

    /**
     * Register columns of the posts list.
     *
     * @return void
     */
    protected function registerColumns()
    {
        $this->hook->action('admin_init', function () {
            $this->getSingularity()->columns($this->columns());
        })->dispatch();
    }
}
