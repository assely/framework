<?php

namespace Assely\Column;

use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Illuminate\Support\Arr;

class ColumnProfile extends Column
{
    use HasSlug, HasTitles;

    /**
     * Construct profile column type.
     *
     * @param \Assely\Profile\Profile $profile
     * @param string $path
     */
    public function __construct($profile, $path)
    {
        $this->profile = $profile;
        $this->path = $path;

        $this->setSlug($this->getField()->getSlug());
        $this->setSingular([$this->getField()->getSingular()]);
    }

    /**
     * Get profile field.
     *
     * @return \Assely\Input\Input|\Assely\Profile\Profile
     */
    public function getField()
    {
        $singularity = $this->profile->getSingularity();

        if (isset($singularity)) {
            return $this->profile->getFields()->find($this->path);
        }

        return $this->profile;
    }

    /**
     * Render column content.
     *
     * @param int $id
     *
     * @return void
     */
    public function render($id)
    {
        $meta = $this->profile->getMeta($id);

        $this->getField()->getManager()->renderPreview(
            Arr::get($meta, $this->path)
        );
    }
}
