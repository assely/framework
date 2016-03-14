<?php

namespace Assely\Support\Facades;

use Assely\Singularity\Model\PosttypeModel;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Singularity\Model\PosttypeModel
 */
class Post extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return PosttypeModel::class;
    }
}
