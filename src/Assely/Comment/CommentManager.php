<?php

namespace Assely\Comment;

use Assely\Singularity\Manager;

class CommentManager extends Manager
{
    /**
     * Bootstrap comment manager.
     *
     * @param \Assely\Comment\Comment $comment
     *
     * @return void
     */
    public function boot($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Register comments list columns.
     *
     * @return void
     */
    public function columns()
    {
        // TODO
    }
}
