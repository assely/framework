<?php

namespace Assely\Adapter;

use Assely\Adapter\Traits\FormatsModificationDate;

class Menu extends Adapter
{
    use FormatsModificationDate;

    /**
     * Menu item children items.
     *
     * @var \Assely\Adapter\Menu[]
     */
    public $children = [];

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    protected $touches = [
        'attr' => 'attr_title',
        'description' => 'description',
        'id' => 'ID',
        'item_id' => 'object_id',
        'item_type' => 'type',
        'link' => 'url',
        'modified_at' => 'post_modified',
        'order' => 'menu_order',
        'parent_id' => 'menu_item_parent',
        'target' => 'target',
        'title' => 'title',
    ];

    /**
     * Connect post adapter.
     *
     * @return void
     */
    public function connect()
    {
        //
    }

    /**
     * Is current menu item active?
     *
     * @return bool
     */
    public function active()
    {
        $classes = $this->getAdaptee()->classes;

        foreach ($classes as $class) {
            if (strpos($class, 'current') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets menu item classes.
     *
     * @return array
     */
    public function classes()
    {
        return $this->getAdaptee()->classes;
    }

    /**
     * Get menu item children.
     *
     * @return \Assely\Adapter\Menu[]
     */
    public function children()
    {
        return $this->children;
    }

    /**
     * Checks if menu have children items.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return  ! empty($this->children);
    }

    /**
     * Set menu item child.
     *
     * @param \Assely\Adapter\Menu $child
     *
     * @return self
     */
    public function setChild(Menu $child)
    {
        array_unshift($this->children, $child);

        return $this;
    }

    /**
     * Set menu item children.
     *
     * @param array $children
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Return adapter key when casting to the string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s/%s-%s',
            get_class($this),
            $this->id,
            strtotime($this->modified_at)
        );
    }

    /**
     * Handle json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'attr' => $this->attr,
            'description' => $this->description,
            'id' => $this->id,
            'item_id' => $this->item_id,
            'item_type' => $this->item_type,
            'link' => $this->link,
            'modified_at' => $this->modified_at,
            'order' => $this->order,
            'parent_id' => $this->parent_id,
            'target' => $this->target,
            'title' => $this->title,
        ];
    }
}
