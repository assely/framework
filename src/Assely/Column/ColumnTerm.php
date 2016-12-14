<?php

namespace Assely\Column;

use Illuminate\Support\Arr;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Assely\Support\Accessors\HasArguments;

class ColumnTerm extends Column
{
    use HasSlug, HasArguments, HasTitles;

    /**
     * Construct taxonomy column.
     *
     * @param \Assely\Taxonomy\Taxonomy $taxonomy
     */
    public function __construct($taxonomy, $path)
    {
        $this->taxonomy = $taxonomy;
        $this->path = $path;

        $this->setSlug($taxonomy->slug);
        $this->setArguments($taxonomy->arguments());
        $this->setSingular([$this->getField()->getSingular()]);
    }

    /**
     * Get taxonomy field.
     *
     * @return \Assely\Input\Input
     */
    public function getField()
    {
        return $this->taxonomy->getFields()->find($this->path);
    }

    /**
     * Render column content.
     *
     * @param int $termId
     *
     * @return void
     */
    public function render($termId)
    {
        $meta = $this->taxonomy->getMeta($termId);

        $this->getField()->getManager()->renderPreview(
            Arr::get($meta, $this->path)
        );
    }
}
