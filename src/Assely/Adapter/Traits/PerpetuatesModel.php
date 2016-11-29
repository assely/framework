<?php

namespace Assely\Adapter\Traits;

trait PerpetuatesModel
{
    /**
     * Save model.
     *
     * @return int|\WP_Error
     */
    public function save()
    {
        return $this->model->update($this);
    }
}
