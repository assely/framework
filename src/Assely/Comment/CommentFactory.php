<?php

namespace Assely\Comment;

use Assely\Foundation\Factory;
use Assely\Contracts\Singularity\Model\ModelInterface;

class CommentFactory extends Factory
{
    /**
     * Create comment.
     *
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface $model
     *
     * @return \Assely\Comment\CommentSingularity
     */
    public function create(ModelInterface $model)
    {
        $comment = $this->container->make(CommentSingularity::class);

        $comment
            ->setModel($model)
            ->boot();

        return $comment;
    }
}
