<?php

namespace Assely\Rewrite;

class Tag
{
    /**
     * @param $parameters
     */
    public function add($parameters, $condition = null)
    {
        if (is_array($parameters)) {
            foreach ($parameters as $parameter => $condition) {
                $this->add($parameter, $condition);
            }

            return;
        }

        return $this->register($parameters, $condition);
    }

    /**
     * @param $parameter
     * @param $condition
     */
    public function register($parameter, $condition)
    {
        return add_rewrite_tag("%{$parameter}%", $condition);
    }
}
