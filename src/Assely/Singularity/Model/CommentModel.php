<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\Comment;
use Assely\Singularity\QueryException;
use Assely\Contracts\Singularity\WPQueryable;

class CommentModel extends MetaboxModel implements WPQueryable
{
    /**
     * Default comment params.
     *
     * @var array
     */
    protected $defaults = [
        'preserve' => 'multiple',
    ];

    /**
     * Query comments.
     *
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function query(array $arguments = [])
    {
        $options = array_merge([
            'hierarchical' => 'threaded',
        ], $arguments);

        return $this->plugger
            ->setModel($this)
            ->setAdapter(Comment::class)
            ->plugIn(get_comments($options))
            ->getConnected();
    }

    /**
     * Find comment by id.
     *
     * @param int $id
     *
     * @return \Assely\Adapter\Comment
     */
    public function find($id)
    {
        return $this->query(['ID' => $id])->first();
    }

    /**
     * Find comment by id or trow if unsuccessful.
     *
     * @param int $id
     *
     * @return \Assely\Adapter\Comment
     */
    public function findOrFail($id)
    {
        $comment = $this->find($id);

        if (! $comment->getAdaptee()) {
            throw new QueryException("Comment [{$id}] not found.");
        }

        return $comment;
    }

    /**
     * Get all comments.
     *
     * @return array
     */
    public function all()
    {
        return $this->query()->all();
    }

    /**
     * Gets post comments.
     *
     * @param string $id
     *
     * @return \Assely\Adapter\Comment
     */
    public function getByPost($id)
    {
        return $this->query(['post_id' => $id]);
    }

    /**
     * Create post.
     *
     * @return bool
     */
    public function create(array $arguments)
    {
        return wp_new_comment($arguments);
    }

    /**
     * Create post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return bool|\WP_Error
     */
    public function createOrFail(array $arguments)
    {
        $status = $this->create($arguments);

        if (is_wp_error($status)) {
            throw new QueryException('Could not create new comment.');
        }

        return $status;
    }

    /**
     * Update post.
     *
     * @param  int $id
     * @param  array $arguments
     *
     * @return int
     */
    public function update(Comment $comment)
    {
        return wp_update_comment((array) $comment->getAdaptee());
    }

    /**
     * Update post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return bool|\WP_Error
     */
    public function updateOrFail(Comment $comment)
    {
        $status = $this->update($comment);

        if (is_wp_error($status)) {
            throw new QueryException("Posttype [{$this->slug}] could not update post with id: {$id}.");
        }

        return $status;
    }

    /**
     * Delete post.
     *
     * @param  int  $id
     * @param  bool $force
     *
     * @return mixed
     */
    public function delete($id, $force = false)
    {
        return wp_delete_comment($id, $force);
    }
}
