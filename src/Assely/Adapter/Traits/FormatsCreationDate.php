<?php

namespace Assely\Adapter\Traits;

trait FormatsCreationDate
{
    /**
     * Gets adaptee create date.
     *
     * @return string
     */
    public function created_at()
    {
        return $this->formatDate($this->adaptee->post_date);
    }
}
