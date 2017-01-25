<?php

namespace Assely\Rewrite;

class Tag
{
    /**
     * Adds rewrite rule tags.
     *
     * @param string|array $parameters
     * @param string|null $condition
     *
     * @return void
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
     * Registers rewrite tag.
     *
     * @param string $parameter
     * @param string $condition
     *
     * @return void
     */
    public function register($parameter, $condition)
    {
        return add_rewrite_tag("%{$parameter}%", $condition);
    }
}
