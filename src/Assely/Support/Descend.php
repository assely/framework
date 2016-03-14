<?php

namespace Assely\Support;

class Descend
{
    /**
     * Return value if is not empty
     * otherwise return default.
     *
     * @param  mixed  &$value
     * @param  mixed  $default
     *
     * @return mixed
     */
    public static function whileEmpty(&$value, $default = '')
    {
        if (! empty($value)) {
            return $value;
        }

        return $default;
    }
}
