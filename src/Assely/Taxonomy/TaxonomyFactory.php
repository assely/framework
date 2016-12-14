<?php

namespace Assely\Taxonomy;

use Assely\Foundation\Factory;
use Assely\Contracts\Singularity\Model\ModelInterface;

class TaxonomyFactory extends Factory
{
    /**
     * Create taxonomy.
     *
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface $model
     * @param  array $belongsTo
     *
     * @return \Assely\Taxonomy\TaxonomySingularity
     */
    public function create(ModelInterface $model, array $belongsTo)
    {
        $taxonomy = $this->container->make(TaxonomySingularity::class);

        $taxonomy
            ->setModel($model)
            ->setBelongsTo($belongsTo)
            ->boot();

        return $taxonomy;
    }
}
