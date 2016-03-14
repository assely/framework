<?php

namespace Assely\Support\Facades;

use Assely\Asset\AssetFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Assely\Asset\AssetFactory
 */
class Asset extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return AssetFactory::class;
    }
}
