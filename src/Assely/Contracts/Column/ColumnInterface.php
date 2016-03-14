<?php

namespace Assely\Contracts\Column;

interface ColumnInterface
{
    /**
     * Render column content.
     *
     * @param mixed $data
     *
     * @return void
     */
    public function render($data);
}
