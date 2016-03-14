<?php

namespace Assely\Field;

class FieldsFinder
{
    /**
     * Fields Collection schema.
     *
     * @var \Assely\Field\Field[]
     */
    protected $schema;

    /**
     * Searching path.
     *
     * @var string
     */
    protected $path;

    /**
     * Find field in schema.
     *
     * @param string $path
     *
     * @return \Assely\Field\Field
     *
     */
    public function find($path)
    {
        $this->setPath($path);

        return $this->search();
    }

    /**
     * Search field in schema.
     *
     * @throws \Assely\Field\FieldException
     *
     * @return \Assely\Field\Field
     */
    public function search()
    {
        // Explode path by dot
        $paths = explode('.', $this->getPath());

        // First we need to filter fields schema and find
        // field with slug of current dot path segment.
        $record = array_filter($this->getSchema(), function ($field) use ($paths) {
            return $field->getSlug() == $paths[0];
        });

        // Grab first item form
        // filtered schema record.
        $field = reset($record);

        // If current path segment is not last segment we need to search
        // futher in field childrens. Before making recusive call,
        // remove current segment path from global paths.
        if ($paths[0] !== end($paths)) {
            return $field->getChildren()->getWithPath(implode('.', array_slice($paths, 1)));
        }

        // If we have field we are done,
        // just return found field.
        if ($field) {
            return $field;
        }

        // We could not find field.
        // Throw an Exception.
        throw new FieldException("We couldn't find Field: " . $this->getPath());
    }

    /**
     * Gets the value of schema.
     *
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Sets the value of schema.
     *
     * @param mixed $schema the schema
     *
     * @return self
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Gets the value of path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the value of path.
     *
     * @param mixed $path the path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}
