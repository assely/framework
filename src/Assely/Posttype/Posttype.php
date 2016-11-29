<?php

namespace Assely\Posttype;

use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\PosttypeModel;

abstract class Posttype extends Repository
{
    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\PosttypeModel
     */
    protected $posttype;

    /**
     * Repository factory.
     *
     * @var \Assely\Posttype\PosttypeFactory
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
     * @param \Assely\Singularity\Model\PosttypeModel $posttype
     * @param \Assely\Posttype\PosttypeFactory $factory
     * @param \Assely\Hook\HookFactory $hook
     */
    public function dispatch(
        PosttypeModel $posttype,
        PosttypeFactory $factory,
        HookFactory $hook
    ) {
        $this->posttype = $posttype;
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
        // Make posttype model for the factory.
        $this->setModel($this->makePosttypeModel());

        // Build posttype with model.
        $this->setSingularity($this->makePosttypeInstance());

        // Set columns to the posttype, after admin init.
        if ($this->methodExist('columns')) {
            $this->registerColumns();
        }

        // Set posttype post templates.
        if ($this->methodExist('templates')) {
            $this->registerTemplates();
        }
    }

    /**
     * Create posttype model.
     *
     * @return \Assely\Singularity\Model\PosttypeModel
     */
    protected function makePosttypeModel()
    {
        return $this->posttype->make($this->slug, $this->arguments());
    }

    /**
     * Create posttype singularity instance.
     *
     * @return \Assely\Posttype\Posttype
     */
    protected function makePosttypeInstance()
    {
        return $this->factory->create($this->getModel());
    }

    /**
     * Register posttype columns.
     *
     * @return void
     */
    protected function registerColumns()
    {
        $this->hook->action('admin_init', function () {
            $this->getSingularity()->columns($this->columns());
        })->dispatch();
    }

    /**
     * Register posttype templates.
     *
     * @return void
     */
    protected function registerTemplates()
    {
        $this->hook->filter('theme_page_templates', function ($templates) {
            return array_merge($templates, $this->templates());
        })->dispatch();
    }
}
