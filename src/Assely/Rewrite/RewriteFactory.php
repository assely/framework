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
     * @param  string|array $points
     * @param  int $places
     *
     * @return \Assely\Rewrite\Endpoint
     */
    public function endpoint($points, $places = null)
    {
        if (is_array($points)) {
            foreach ($points as $point => $place) {
                $this->endpoint($point, $place);
            }

            return;
        }

        $endpoint = $this->container->make(Endpoint::class, [$points, $places]);

        $endpoint->add();

        return $this->hang($endpoint);
    }
}
