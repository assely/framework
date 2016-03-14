<?php

namespace Assely\Comment;

use Assely\Column\ColumnsCollection;
use Assely\Singularity\Singularity;
use Assely\Support\Accessors\HoldsColumns;

class CommentSingularity extends Singularity
{
    use HoldsColumns;

    /**
     * Comment manager.
     *
     * @var \Assely\Comment\CommentManager
     */
    public $manager;

    /**
     * Construct postype.
     *
     * @param \Assely\Comment\CommentManager $manager
     * @param \Assely\Column\ColumnsCollection $columns
     * @param \Assely\Contracts\Singularity\Model\ModelInterface $model
     */
    public function __construct(
        CommentManager $manager,
        ColumnsCollection $columns
    ) {
        $this->manager = $manager;
        $this->columns = $columns;
    }

    /**
     * Set comment columns columns.
     *
     * @param array $columns
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
