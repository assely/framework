<?php

namespace Assely\Adapters\Traits;

trait FormatsModificationDate
{
    /**
     * Gets adaptee modify date.
     *
     * @return string
     */
    public function modified_at()
    {
        return $this->formatDate($this->adaptee->post_modified);
    }
}
