<?php

namespace Assely\Adapter;

use Assely\Adapter\Traits\PerpetuatesModel;

class Comment extends Adapter
{
    use PerpetuatesModel;

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    public $touches = [
        'agent' => 'comment_agent',
        'approved' => 'comment_approved',
        'author' => 'comment_author',
        'author_email' => 'comment_author_email',
        'author_ip' => 'comment_author_IP',
        'author_url' => 'comment_author_url',
        'content' => 'comment_content',
        'created_at' => 'comment_date',
        'id' => 'comment_ID',
        'karma' => 'comment_karma',
        'parent_id' => 'comment_parent',
        'post_id' => 'comment_post_ID',
        'type' => 'comment_type',
        'user_id' => 'user_id',
    ];

    /**
     * Gets adaptee create date.
     *
     * @return string
     */
    public function created_at()
    {
        return $this->formatDate($this->adaptee->comment_date);
    }

    /**
     * Get replies comments.
     *
     * @return \Illuminate\Support\Collection
     */
    public function replies()
    {
        $adaptees = $this->adaptee->get_children();

        return $this->model->plugAdapter(self::class, $adaptees);
    }

    /**
     * Checks if comment have any replies.
     *
     * @return bool
     */
    public function hasReplies()
    {
        return  ! empty($this->adaptee->get_children());
    }

    /**
     * Handle json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'agent' => $this->agent,
            'approved' => $this->approved,
            'author' => $this->author,
            'author_email' => $this->author_email,
            'author_ip' => $this->author_ip,
            'author_url' => $this->author_url,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'id' => $this->id,
            'karma' => $this->karma,
            'parent_id' => $this->parent_id,
            'post_id' => $this->post_id,
            'replies' => $this->replies,
            'type' => $this->type,
            'user_id' => $this->user_id,
        ];
    }
}
