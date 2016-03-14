<?php

namespace Assely\Contracts\Singularity;

interface ValidatesScreenInterface
{
    /**
     * Validates current screen conditions.
     *
     * @return boolean
     */
    public function isValidScreen();
}
