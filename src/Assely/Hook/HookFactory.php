<?php

namespace Assely\Hook;

class HookFactory
{
    /**
     * Make action hook.
     *
     * @param string $slug
     * @param mixed $callback
     * @param array $arguments
     *
     * @return \Assely\Hook\Hook
     */
    public function action($slug, $callback = null, $arguments = [])
    {
        return new Hook('action', $slug, $callback, $arguments);
    }

    /**
     * Make filter hook.
     *
     * @param string$slug
     * @param mixed $callback
     * @param array $arguments
     *
     * @return \Assely\Hook\Hook
     */
    public function filter($slug, $callback = null, $arguments = [])
    {
        return new Hook('filter', $slug, $callback, $arguments);
    }
}
