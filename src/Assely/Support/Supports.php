<?php

namespace Assely\Support;

class Supports
{
    /**
     * Add support for options.
     *
     * @param array|string $options
     * @param mixed $arguments
     *
     * @return self
     */
    public function add($options, $arguments = true)
    {
        if (is_array($options)) {
            return $this->register($options);
        }

        return $this->register([$options => $arguments]);
    }

    /**
     * Get support option value.
     *
     * @param  string $option
     *
     * @return mixed
     */
    public static function get($option)
    {
        return get_theme_support($option);
    }

    /**
     * Register support options.
     *
     * @param array $options
     *
     * @return void
     */
    protected function register(array $options)
    {
        foreach ($options as $option => $argument) {
            if (is_bool($argument)) {
                add_theme_support($option);

                continue;
            }

            add_theme_support($option, $argument);
        }
    }
}
