<?php

namespace Assely\Support\Facades;

use Assely\Singularity\Model\CommentModel;
use Illuminate\Support\Facades\Facade;

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
