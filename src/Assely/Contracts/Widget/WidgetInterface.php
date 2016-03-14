<?php

namespace Assely\Contracts\Widget;

interface WidgetInterface
{
    /**
     * Output widget view.
     *
     * @param array $instance
     *
     * @return void
     */
    public function render($instance);

    /**
     * Output widget fields.
     *
     * @param array $instance
     *
     * @return void
     */
    public function fields($instance);
}
