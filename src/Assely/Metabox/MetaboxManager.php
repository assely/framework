<?php

namespace Assely\Metabox;

use Assely\Singularity\Manager;

class MetaboxManager extends Manager
{
    /**
     * Boot metabox.
     *
     * @param \Assely\Metabox\Metabox $assistant
     *
     * @return void
     */
    public function boot($metabox)
    {
        $this->metabox = $metabox;

        $this->hooks();
    }

    /**
     * Register metabox hooks.
     *
     * @return void
     */
    public function hooks()
    {
        // Register metabox
        // on admin init.
        $this->hook->action(
            'admin_init',
            [$this->metabox, 'register']
        )->dispatch();

        // Save all metabox meta
        // data on post save.
        $this->hook->action('save_post', function ($id) {
            $this->metabox->getModel()->setContext('post');

            $this->metabox->save($id);
        })->dispatch();

        // Save metabox meta data
        // on the comment save.
        $this->hook->action('edit_comment', function ($id) {
            $this->metabox->getModel()->setContext('comment');

            $this->metabox->save($id);
        })->dispatch();
    }

    /**
     * Dispach metabox.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->hook->action(
            'admin_print_footer_scripts',
            [$this, 'script']
        )->dispatch();

        echo $this->view->make('Assely::Metabox.metabox', [
            'slug' => $this->metabox->getModel()->getSlug(),
            'fingerprint' => $this->metabox->getModel()->getFingerprint(),
        ]);
    }

    /**
     * Render metabox script.
     *
     * @return void
     */
    public function script()
    {
        echo $this->view->make('Assely::script', [
            'slug' => $this->metabox->getModel()->getSlug(),
            'fingerprint' => $this->metabox->getModel()->getFingerprint(),
            'fields' => json_encode($this->metabox->getFields()->all()),
        ]);
    }
}
