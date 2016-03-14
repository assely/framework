<?php

namespace Assely\Metabox;

use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\MetaboxModel;

abstract class Metabox extends Repository
{
    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\MetaboxModel
     */
    protected $metabox;

    /**
     * Repository factory.
     *
     * @var \Assely\Metabox\MetaboxFactory
     */
    protected $factory;

    /**
     * Hook service.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Dispatch repository.
     *
     * @param \Assely\Singularity\Model\MetaboxModel $metabox
     * @param \Assely\Metabox\MetaboxFactory $factory
     * @param \Assely\Hook\HookFactory $hook
     *
     * @return void
     */
    public function dispatch(
        MetaboxModel $metabox,
        MetaboxFactory $factory,
        HookFactory $hook
    ) {
        $this->metabox = $metabox;
        $this->factory = $factory;
        $this->hook = $hook;
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function register()
    {
        // Make metabox model for the factory.
        $this->setModel($this->makeMetaboxModel());

        // Build metabox with model and singularity holders.
        $this->setSingularity($this->makeMetaboxInstance());

        // Set fields to the metabox.
        if ($this->methodExist('fields')) {
            $this->registerFields();
        }
    }

    /**
     * Create metabox model.
     *
     * @return \Assely\Singularity\Model\MetaboxModel
     */
    protected function makeMetaboxModel()
    {
        return $this->metabox->make($this->slug, $this->arguments());
    }

    /**
     * Create metabox singularity instance.
     *
     * @return \Assely\Metabox\Metabox
     */
    protected function makeMetaboxInstance()
    {
        return $this->factory->create($this->getModel(), $this->resolveHoldersSlugs());
    }

    /**
     * Register singularity fields.
     *
     * @return \Assely\Metabox\Metabox
     */
    protected function registerFields()
    {
        return $this->getSingularity()->fields($this->fields());
    }
}
