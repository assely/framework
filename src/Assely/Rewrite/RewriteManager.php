<?php

namespace Assely\Rewrite;

use Assely\Singularity\Manager;

class RewriteManager extends Manager
{
    /**
     * Bootstrap posttype manager.
     *
     * @param \Assely\Rewrite\Rewrite $posttype
     *
     * @return void
     */
    public function boot($rule)
    {
        $this->hook->action(
            'init',
            [$rule, 'register']
        )->dispatch();
    }
}
