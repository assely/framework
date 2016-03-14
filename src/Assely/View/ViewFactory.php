<?php

namespace Assely\View;

use Assely\Foundation\Factory;

class ViewFactory extends Factory
{
    /**
     * Make view.
     *
     * @param string $name
     * @param array $data
     *
     * @return string
     */
    public function make($name, $data = [])
    {
        $view = $this->container->make('view');

        echo $view->make($name, $data)->render();
    }
}
