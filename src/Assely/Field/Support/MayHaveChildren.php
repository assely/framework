<?php

namespace Assely\Field\Support;

trait MayHaveChildren
{
    /**
     * Field children fields.
     *
     * @var \Assely\Field\Field[]
     */
    protected $children;

    /**
     * Set field children fields.
     *
     * @param \Assely\Field\Field[] $fields
     * @return self
     */
    public function children($fields)
    {
        $this->getChildren()->mergeSchema($fields);

        return $this;
    }

    /**
     * There are any children?
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return  ! empty($this->getChildren()->getSchema());
    }

    /**
     * Gets the value of children.
     *
     * @return \Assely\Field\FieldsCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the value of children
     *
     * @param @return \Assely\Field\FieldsCollection $children
     *
     * @return self
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }
}
