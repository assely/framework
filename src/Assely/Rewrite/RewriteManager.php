<?php

namespace Assely\Rewrite;

use Assely\Singularity\Manager;

class RewriteManager extends Manager
{
    /**
     * Bootstraps rule manager.
     *
     * @param \Assely\Rewrite\Rewrite $rewrite
     *
     * @return void
     */
    public function boot(Rewrite $rewrite)
    {
        $this->hook->action(
            'init',
            [$rewrite, 'register']
        )->dispatch();
    }
}
