<?php

namespace Assely\Column;

use Illuminate\Support\Arr;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;

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
        return $this->metabox->getFields()->find($this->path);
    }

    /**
     * Render column content.
     *
     * @param int $postId
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
