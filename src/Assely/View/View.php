<?php

namespace Assely\View;

use Assely\View\ViewEngine;

class View
{
    /**
     * Construct view.
     *
     * @param \Assely\View\ViewEngine $engine
     */
    public function __construct(ViewEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Render view.
     *
     * @param  string $view
     * @param  array  $data
     *
     * @return void
     */
    public function render($view, $data = [])
    {
        $this->engine->render($view, $data);
    }
}
