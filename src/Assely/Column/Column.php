<?php

namespace Assely\Column;

use Assely\Contracts\Column\ColumnInterface;

abstract class Column implements ColumnInterface
{
    /**
     * Column default arguments.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Renders content of the column.
     *
     * @param int $id
     *
     * @return void
     */
    abstract public function render($id);
}
