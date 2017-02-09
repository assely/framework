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

        $rewrite->setPattern($pattern);

        return $this->hang($rewrite);
    }

    /**
     * Create rewrite endpoint.
     *
     * @param  string $points
     * @param  int $places
     *
     * @return \Assely\Rewrite\Endpoint
     */
    public function endpoint($point)
    {
        $endpoint = $this->container->make(Endpoint::class);

        $endpoint
            ->setPoint($point)
            ->add();

        return $this->hang($endpoint);
    }
}
