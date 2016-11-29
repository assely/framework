<?php

namespace Assely\Column;

use Illuminate\Support\Arr;

class ColumnsCollection
{
    /**
     * Columns collection.
     *
     * @var \Assely\Support\Facades\Column[]
     */
    private $columns;

    /**
     * Construct columns collection.
     *
     * @param \Assely\Support\Facades\Column[] $columns
     * @return void
     */
    public function __construct(array $columns = [])
    {
        $this->setColumns($columns);
    }

    /**
     * Map columns.
     *
     * @param \Assely\Support\Facades\Column[] $columns
     * @return array
     */
    public function makeAssociativeCollection(array $columns)
    {
        $mapped = [];

        foreach ($columns as $column) {
            Arr::set($mapped, $column->getSlug(), $column);
        }

        return $mapped;
    }

    /**
     * Set columns names.
     *
     * @param array $names
     * @return array
     */
    public function setNames($names)
    {
        foreach ($this->columns as $key => $column) {
            Arr::set($names, $key, $column->getSingular());
        }

        return $names;
    }

    /**
     * Manage column content.
     *
     * @param  string $name
     * @return void
     */
    public function manageContent($name)
    {
        global $post;

        $this->renderContent($name, $post->ID);
    }

    /**
     * Manage columns content.
     *
     * @param string $name
     * @return void
     */
    public function renderContent($name, $id)
    {
        $this->getColumn($name)->render($id);
    }

    /**
     * Gets the value of columns.
     *
     * @return \Assely\Support\Facades\Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Gets the value of column.
     *
     * @param string $name
     * @return array
     */
    public function getColumn($name)
    {
        return Arr::get($this->columns, $name);
    }

    /**
     * Sets the value of columns.
     *
     * @param array $columns
     * @return self
     */
    public function setColumns(array $columns)
    {
        $this->columns = $this->makeAssociativeCollection($columns);

        return $this;
    }
}
