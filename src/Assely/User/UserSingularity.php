<?php

namespace Assely\User;

use Assely\Singularity\Singularity;
use Assely\Column\ColumnsCollection;
use Assely\Support\Accessors\HoldsColumns;

class UserSingularity extends Singularity
{
    use HoldsColumns;

    /**
     * Construct user singularity.
     *
     * @param \Assely\Profile\ProfileManager $manager
     * @param \Assely\Column\ColumnsCollection $columns
     */
    public function __construct(
        UserManager $manager,
        ColumnsCollection $columns
    ) {
        $this->manager = $manager;
        $this->columns = $columns;
    }

    /**
     * Set users list columns.
     *
     * @param \Assely\Support\Facades\Column[] $columns
     *
     * @return self
     */
    public function columns(array $columns)
    {
        $this->columns->setColumns($columns);

        $this->manager->columns();

        return $this;
    }
}
