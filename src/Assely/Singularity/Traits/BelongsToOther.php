<?php

namespace Assely\Singularity\Traits;

trait BelongsToOther
{
    /**
     * Where singularity belongs to.
     *
     * @var array
     */
    protected $belongsTo;

    /**
     * Gets where singularity belongs to.
     *
     * @return array
     */
    public function getBelongsTo()
    {
        return $this->belongsTo;
    }

    /**
     * Sets where singularity belongs to.
     *
     * @param array $belongsTo
     *
     * @return self
     */
    public function setBelongsTo(array $belongsTo)
    {
        $this->belongsTo = $belongsTo;

        return $this;
    }
}
