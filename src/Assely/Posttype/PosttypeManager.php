<?php

namespace Assely\Posttype;

use Assely\Singularity\Manager;

class PosttypeManager extends Manager
{
    /**
     * Bootstrap posttype manager.
     *
     * @param \Assely\Posttype\Posttype $posttype
     *
     * @return void
     */
    public function boot($posttype)
    {
        $this->posttype = $posttype;

        $this->hook->action(
            'init',
            [$this->posttype, 'register']
        )->dispatch();
    }

    /**
     * Register posttype list columns.
     *
     * @return void
     */
    public function columns()
    {
        $this->hook->filter(
            "manage_edit-{$this->posttype->getModel()->getSlug()}_columns",
            [$this->posttype->getColumns(), 'setNames']
        )->dispatch();

        $this->hook->action(
            "manage_{$this->posttype->getModel()->getSlug()}_posts_custom_column",
            [$this->posttype->getColumns(), 'manageContent']
        )->dispatch();
    }
}
