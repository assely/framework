<?php

namespace Assely\Support\Accessors;

use Illuminate\Support\Str;

trait HasTitles
{
    /**
     * Singular title.
     *
     * @var string
     */
    protected $singular;

    /**
     * Plural title.
     *
     * @var string
     */
    protected $plural;

    /**
     * Gets the value of singular.
     *
     * @return string
     */
    public function getSingular()
    {
        return $this->singular;
    }

    /**
     * Sets the value of singular.
     *
     * @param mixed $singular the singular
     *
     * @return self
     */
    public function setSingular($title = [])
    {
        if (! empty($title) && isset($title[0])) {
            $this->singular = $title[0];
        } else {
            $name = str_replace('_', ' ', $this->getSlug());

            $this->singular = Str::singular(Str::title($name));
        }

        return $this;
    }

    /**
     * Gets the value of plural.
     *
     * @return string
     */
    public function getPlural()
    {
        return $this->plural;
    }

    /**
     * Sets the value of plural.
     *
     * @param mixed $plural the plural
     *
     * @return self
     */
    public function setPlural($title = [])
    {
        if (! empty($title) && isset($title[1])) {
            $this->plural = $title[1];
        } else {
            $name = str_replace('_', ' ', $this->getSlug());

            $this->plural = Str::plural(Str::title($name));
        }

        return $this;
    }

    /**
     * Get labels.
     *
     * @return array
     */
    public function getLabels()
    {
        return [
            'name' => $this->plural,
            'singular_name' => $this->singular,
            'add_new' => 'Add New'.' '.$this->singular,
            'add_new_item' => 'Add New'.' '.$this->singular,
            'edit_item' => 'Edit'.' '.$this->singular,
            'new_item' => 'New'.' '.$this->singular,
            'all_items' => 'All'.' '.$this->plural,
            'view_item' => 'View'.' '.$this->singular,
            'search_items' => 'Search'.' '.$this->plural,
            'not_found' => $this->plural.' '.'no found',
            'not_found_in_trash' => $this->plural.' '.'no found in Trash',
            'parent_item_colon' => '',
            'menu_name' => $this->plural,
        ];
    }
}
