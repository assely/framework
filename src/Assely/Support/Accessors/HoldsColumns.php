<?php

namespace Assely\Support\Accessors;

trait HoldsColumns
{
    /**
     * List of columns.
     *
     * @var \Assely\Column\ColumnsCollection
     */
    protected $columns;

    /**
     * Gets the value of columns.
     *
     * @return \Assely\Column\ColumnsCollection
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Gets the column by slug.
     *
     * @param string $slug
     *
     * @return \Assely\Contracts\Column\ColumnInterface
     */
    public function getColumn($slug)
    {
        return $this->columns->getColumn($slug);
    }
}
