<?php

namespace Assely\Support\Accessors;

use Assely\Support\Inflector;

trait HasSlug
{
    /**
     * Slug value.
     *
     * @var string
     */
    protected $slug;

    /**
     * Gets the value of slug.
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the value of slug.
     *
     * @param mixed $slug the slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = Inflector::slugify($slug);

        return $this;
    }
}
