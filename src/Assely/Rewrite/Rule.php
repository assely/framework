<?php

namespace Assely\Rewrite;

class Rule
{
    /**
     * @var mixed
     */
    protected $regrex;

    /**
     * @var mixed
     */
    protected $guid;

    /**
     * @var mixed
     */
    protected $parameters;

    /**
     * @param $pattern
     */
    public function resolve($pattern)
    {
        # code...
    }

    public function add()
    {
        return add_rewrite_rule('^submit/?$', 'index.php?form=true');
    }
}
