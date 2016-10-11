<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\CommentAdapter;
use Assely\Contracts\Singularity\WPQueryable;
use Assely\Singularity\QueryException;

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

        return $this->plugAdapter(CommentAdapter::class, get_comments($options));
    }

    /**
     * Find comment by id.
     *
     * @param integer $id
     *
     * @return \Assely\Adapter\CommentAdapter
     */
    public function find($id)
    {
        return $this->query(['ID' => $id])->first();
    }

    /**
     * Find comment by id or trow if unsuccessful.
     *
     * @param integer $id
     *
     * @return \Assely\Adapter\CommentAdapter
     */
    public function findOrFail($id)
    {
        $comment = $this->find($id);

        if (! $comment->getWrappedObject()) {
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
     * @return \Assely\Adapter\CommentAdapter
     */
    public function getByPost($id)
    {
        return $this->query(['post_id' => $id]);
    }

    /**
     * Create post.
     *
     * @return boolean
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
     * @return boolean|\WP_Error
     *
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
     * @param  integer $id
     * @param  array $arguments
     *
     * @return integer
     */
    public function update(CommentAdapter $comment)
    {
        return wp_update_comment((array) $comment->getAdaptee());
    }

    /**
     * Update post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return boolean|\WP_Error
     *
     */
    public function updateOrFail(CommentAdapter $comment)
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
     * @param  integer  $id
     * @param  boolean $force
     *
     * @return mixed
     */
    public function delete($id, $force = false)
    {
        return wp_delete_comment($id, $force);
    }
}
