<?php

namespace Assely\Input\Support;

trait HasAPreview
{
    /**
     * Get field preview and render in the blade.
     *
     */
    public function preview($value)
    {
        ob_start();

        require $this->getTemplate('prebladePath') . $this->getTemplate('filename');

        return ob_get_clean();
    }
}
