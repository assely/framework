<?php

namespace Assely\Field\Support;

use Illuminate\Contracts\View\Factory as ViewFactory;

trait HasAPreview
{
    /**
     * Get field preview and render.
     *
     * @return void
     */
    abstract public function preview(ViewFactory $view, $value);
}
