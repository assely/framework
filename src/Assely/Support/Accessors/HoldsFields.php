<?php

namespace Assely\Support\Accessors;

trait HoldsFields
{
    /**
     * Field collection.
     *
     * @var array
     */
    protected $fields;

    /**
     * Gets the Collection of fields to reder.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
