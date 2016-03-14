<?php

namespace Assely\Support;

class Inflector
{
    /**
     * Slugify passed string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function slugify($string)
    {
        return sanitize_title($string);
    }
}
