<?php

namespace Assely\Column;

use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Illuminate\Support\Arr;

class ColumnMetabox extends Column
{
    use HasSlug, HasTitles;

    /**
     * Construct column.
     *
     * @param \Assely\Metabox\MetaboxRepository $metabox
     * @param string $path
     */
    public function __construct($metabox, $path)
    {
        $this->metabox = $metabox;
        $this->path = $path;

        $this->setSlug($this->getField()->getSlug());
        $this->setSingular([$this->getField()->getSingular()]);
    }

    /**
     * Get metabox field.
     *
     * @return \Assely\Input\Input
     */
    public function getField()
    {
        return $this->metabox->getFields()->getWithPath($this->path);
    }

    /**
     * Render column content
     *
     * @param integer $postId
     *
     * @return void
     */
    public function render($postId)
    {
        $meta = $this->metabox->getMeta($postId);

        $this->getField()->getManager()->renderPreview(
            Arr::get($meta, $this->path)
        );
    }
}
