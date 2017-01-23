<?php

namespace Assely\Rewrite;

use Assely\Foundation\Depot;

class RewriteFactory extends Depot
{
    /**
     * Create rewrite rule.
     *
     * @param  string $pattern
     *
     * @return \Assely\Rewrite\Rule
     */
    public function rule($pattern)
    {
        $rewrite = $this->container->make(Rewrite::class);

        $this->hang($rewrite->setPattern($pattern));

        return $rewrite->getRule();
    }
}
