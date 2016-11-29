<?php

namespace Assely\Comment;

use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\CommentModel;

class Comment extends Repository
{
    /**
     * Repository slug.
     *
     * @var string
     */
    public $slug = 'comment';

    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\CommentModel
     */
    protected $comment;

    /**
     * Repository factory.
     *
     * @var \Assely\Comment\CommentFactory
     */
    protected $factory;

    /**
     * Hook service.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Dispatch repository.
     *
     * @param \Assely\Singularity\Model\CommentModel $comment
     * @param \Assely\Comment\CommentFactory $factory
     * @param \Assely\Hook\HookFactory $hook
     *
     * @return void
     */
    public function dispatch(
        CommentModel $comment,
        CommentFactory $factory,
        HookFactory $hook
    ) {
        $this->comment = $comment;
        $this->factory = $factory;
        $this->hook = $hook;
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function register()
    {
        // Make comment model for the factory.
        $this->setModel($this->makeCommentModel());

        // Build comment with model.
        $this->setSingularity($this->makeCommentInstance());

        // Set columns to the comment, after admin init.
        if ($this->methodExist('columns')) {
            $this->registerColumns();
        }
    }

    /**
     * Create metabox model.
     *
     * @return \Assely\Singularity\Model\CommentModel
     */
    protected function makeCommentModel()
    {
        return $this->comment->make($this->slug);
    }

    /**
     * Create metabox singularity instance.
     *
     * @return \Assely\Comment\Comment
     */
    protected function makeCommentInstance()
    {
        return $this->factory->create($this->getModel());
    }

    /**
     * Register comment columns.
     *
     * @return void
     */
    protected function registerColumns()
    {
        $this->hook->action('admin_init', function () {
            $this->getSingularity()->columns($this->columns());
        })->dispatch();
    }
}
