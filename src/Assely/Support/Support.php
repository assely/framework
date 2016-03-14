<?php

namespace Assely\Support;

class Support
{
    /**
     * List of enabled support options.
     *
     * @var array
     */
    public $options;

    /**
     * Construct support.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        $this->register();
    }

    /**
     * Add support for options.
     *
     * @param array|string $options
     * @param array  $arguments
     *
     * @return self
     */
    public static function add($options, array $arguments = [])
    {
        if (is_string($options)) {
            return new static([$options => $arguments]);
        }

        return new static($options);
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
     * @return void
     */
    public function register()
    {
        foreach ($this->options as $option => $arguments) {
            if (is_bool($arguments)) {
                add_theme_support($option);

                continue;
            }

            add_theme_support($option, $arguments);
        }
    }
}
