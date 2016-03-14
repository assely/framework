<?php

namespace Assely\Contracts\Singularity;

interface WPQueryable
{
    /**
     * Query model objects.
     *
     * @param  array $arguments
     */
    public function query(array $arguments);
}
