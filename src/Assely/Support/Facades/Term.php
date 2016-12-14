<?php

namespace Assely\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Assely\Singularity\Model\TaxonomyModel;

/**
 * @see \Assely\Singularity\Model\TaxonomyModel
 */
class Term extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return TaxonomyModel::class;
    }
}
