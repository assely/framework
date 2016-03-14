<?php

namespace Assely\Contracts\Singularity;

interface FillableFieldsInterface
{
    /**
     * Fill singularity fields.
     *
     * @param mixed $object
     */
    public function fill($object);
}
