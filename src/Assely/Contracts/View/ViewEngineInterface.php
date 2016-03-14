<?php

namespace Assely\View;

interface ViewEngineInterface
{
    /**
     * Render view template.
     *
     * @param string $view
     * @param array $data
     */
    public function render($view, $data);
}
