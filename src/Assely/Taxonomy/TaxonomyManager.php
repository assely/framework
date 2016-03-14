<?php

namespace Assely\Taxonomy;

use Assely\Singularity\Manager;

class TaxonomyManager extends Manager
{
    /**
     * Taxonomy singularity instance.
     *
     * @var \Assely\Taxonomy\TaxonomySingularity
     */
    protected $taxonomy;

    /**
     * Boot taxonomy manager.
     *
     * @param \Assely\Taxonomy\Taxonomy $taxonomy
     *
     * @return void
     */
    public function boot($taxonomy)
    {
        $this->taxonomy = $taxonomy;

        $this->hooks();
    }

    /**
     * Register taxonomy hooks.
     *
     * @return void
     */
    public function hooks()
    {
        $this->hook->action(
            'init',
            [$this->taxonomy, 'register']
        )->dispatch();

        $this->hook->action(
            "{$this->taxonomy->getModel()->getSlug()}_add_form_fields",
            [$this->taxonomy, 'fill']
        )->dispatch();

        $this->hook->action(
            "{$this->taxonomy->getModel()->getSlug()}_edit_form_fields",
            [$this->taxonomy, 'fill']
        )->dispatch();

        $this->hook->action(
            "created_{$this->taxonomy->getModel()->getSlug()}",
            [$this->taxonomy, 'save']
        )->dispatch();

        $this->hook->action(
            "edited_{$this->taxonomy->getModel()->getSlug()}",
            [$this->taxonomy, 'save']
        )->dispatch();
    }

    /**
     * Register taxonomy columns hooks.
     *
     * @return void
     */
    public function columns()
    {
        $this->hook->filter(
            "manage_edit-{$this->taxonomy->getModel()->getSlug()}_columns",
            [$this->taxonomy->getColumns(), 'setNames']
        )->dispatch();

        $this->hook->filter(
            "manage_{$this->taxonomy->getModel()->getSlug()}_custom_column",
            function ($value, $name, $id) {
                return $this->taxonomy->getColumn($name)->render($id);
            },
            ['numberOfArguments' => 3]
        )->dispatch();
    }

    /**
     * Dispach taxonomy to the view.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->hook->action(
            'admin_print_footer_scripts',
            [$this, 'script']
        )->dispatch();

        echo $this->view->make('Assely::Taxonomy/taxonomy', [
            'slug' => $this->taxonomy->getModel()->getSlug(),
            'fingerprint' => $this->taxonomy->getModel()->getFingerprint(),
        ]);
    }

    /**
     * Render taxonomy script.
     *
     * @return void
     */
    public function script()
    {
        echo $this->view->make('Assely::script', [
            'slug' => $this->taxonomy->getModel()->getSlug(),
            'fingerprint' => $this->taxonomy->getModel()->getFingerprint(),
            'fields' => json_encode($this->taxonomy->getFields()->all()),
        ]);
    }
}
