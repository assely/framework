<?php

namespace Assely\Support\Facades;

use Assely\Singularity\Model\UserModel;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Singularity\Model\UserModel
 */
class User extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return UserModel::class;
    }
}
