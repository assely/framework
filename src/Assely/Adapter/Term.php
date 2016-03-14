<?php

namespace Assely\Adapter;

use Assely\Adapter\Traits\PerpetuatesModel;

class Term extends Adapter
{
    use PerpetuatesModel;

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    public $touches = [
        'count' => 'count',
        'description' => 'description',
        'group' => 'term_group',
        'id' => 'term_id',
        'parent_id' => 'parent',
        'slug' => 'slug',
        'taxonomy_id' => 'term_taxonomy_id',
        'taxonomy_slug' => 'taxonomy',
        'title' => 'name',
    ];

    /**
     * Connect term adapter.
     *
     * @return void
     */
    public function connect()
    {
        //
    }

    /**
     * Term meta data.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function meta($key = null)
    {
        if (isset($key)) {
            return $this->model->findMeta($this->id, $key);
        }

        return $this->model->getMeta($this->id);
    }

    /**
     * Get posts with this term.
     *
     * @param string $posttype
     *
     * @return \Illuminate\Support\Collection
     */
    public function posts($posttype = null, $arguments = [])
    {
        return $this->model->postsWith($this, $posttype, $arguments);
    }

    /**
     * Handle json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'count' => $this->count,
            'description' => $this->description,
            'group' => $this->group,
            'id' => $this->id,
            'meta' => $this->rejectHiddenMeta($this->meta),
            'parent_id' => $this->parent_id,
            'slug' => $this->slug,
            'taxonomy_id' => $this->taxonomy_id,
            'taxonomy_slug' => $this->taxonomy_slug,
            'title' => $this->title,
        ];
    }
}
