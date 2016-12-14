<?php

namespace Assely\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Assely\Singularity\Model\CommentModel;

/**
 * @see \Assely\Singularity\Model\CommentModel
 */
class Comment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return CommentModel::class;
    }
}
