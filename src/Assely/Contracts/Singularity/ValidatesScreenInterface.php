<?php

namespace Assely\Contracts\Singularity;

interface ValidatesScreenInterface
{
    /**
     * Validates current screen conditions.
     *
     * @return bool
     */
    public function isValidScreen();
}
