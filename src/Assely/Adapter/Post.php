<?php

namespace Assely\Adapter;

use Assely\Thumbnail\Image;
use Assely\Support\Facades\Config;
use Assely\Config\ApplicationConfig;
use Assely\Adapter\Traits\PerpetuatesModel;
use Assely\Adapter\Traits\FormatsCreationDate;
use Assely\Adapter\Traits\FormatsModificationDate;

class Post extends Adapter
{
    use PerpetuatesModel, FormatsCreationDate, FormatsModificationDate;

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    protected $touches = [
        'author' => 'post_author',
        'comment_count' => 'comment_count',
        'comment_status' => 'comment_status',
        'content' => 'post_content',
        'created_at' => 'post_date',
        'excerpt' => 'post_excerpt',
        'id' => 'ID',
        'menu_order' => 'menu_order',
        'mime_type' => 'post_mime_type',
        'modified_at' => 'post_modified',
        'parent_id' => 'post_parent',
        'password' => 'post_password',
        'ping' => 'to_ping',
        'ping_status' => 'ping_status',
        'pinged' => 'pinged',
        'slug' => 'post_name',
        'status' => 'post_status',
        'title' => 'post_title',
        'type' => 'post_type',
    ];

    /**
     * Destroy post.
     *
     * @return void
     */
    public function destroy()
    {
        return $this->model->delete($this->id);
    }

    /**
     * Gets post link.
     *
     * @return string
     */
    public function link()
    {
        return get_permalink($this->id);
    }

    /**
     * Post meta.
     *
     * @param string $key
     *
     * @return array|string
     */
    public function meta($key = null)
    {
        if (isset($key)) {
            return $this->model->findMeta($this->id, $key);
        }

        return $this->model->getMeta($this->id);
    }

    /**
     * Get post terms.
     *
     * @param int $id
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function terms($taxonomy = null, array $arguments = [])
    {
        if (isset($taxonomy)) {
            return $this->model->getTerms($this, $taxonomy, $arguments);
        }

        return $this->model->getAllTerms($this);
    }

    /**
     * Get post comments.
     *
     * @param  array  $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function comments(array $arguments = [])
    {
        $parameters = array_merge($arguments, [
            'post_id' => $this->id,
        ]);

        return $this->model->getComments($parameters);
    }

    /**
     * Get post template.
     *
     * @return string
     */
    public function template()
    {
        return $this->meta('_wp_page_template');
    }

    /**
     * Post has template with name.
     *
     * @return string
     */
    public function isTemplate($name)
    {
        return $this->template === $name;
    }

    /**
     * Get post thumbnail.
     *
     * @return
     */
    public function thumbnail($size = null)
    {
        $size = ($size) ? $size : $this->config->get('images.size');

        if ($id = $this->thumbnailId) {
            return new Image($id, $size);
        }
    }

    /**
     * If the post has a thumbnail?
     *
     * @return bool
     */
    public function hasThumbnail()
    {
        return ! is_null($this->thumbnail);
    }

    /**
     * Get post thumbnail id.
     *
     * @return int
     */
    public function thumbnailId()
    {
        return get_post_thumbnail_id($this->id);
    }

    /**
     * Gets post format.
     *
     * @return string
     */
    public function format()
    {
        return get_post_format($this->id);
    }

    /**
     * Checks if post has given format.
     *
     * @return string
     */
    public function hasFormat($type)
    {
        return has_post_format($type, $this->id);
    }

    /**
     * Checks if post has given format.
     *
     * @return string
     */
    public function setFormat($type)
    {
        return set_post_format($this->id, $type);
    }

    /**
     * Return adapter key when casting to the string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s/%s-%s',
            get_class($this),
            $this->id,
            strtotime($this->modified_at)
        );
    }

    /**
     * Handle json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'author' => $this->author,
            'comment_count' => $this->comment_count,
            'comment_status' => $this->comment_status,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'excerpt' => $this->excerpt,
            'format' => $this->format,
            'id' => $this->id,
            'link' => $this->link,
            'menu_order' => $this->menu_order,
            'meta' => $this->rejectHiddenMeta($this->meta),
            'mime_type' => $this->mime_type,
            'modified_at' => $this->modified_at,
            'parent_id' => $this->parent_id,
            'password' => $this->password,
            'ping' => $this->ping,
            'ping_status' => $this->ping_status,
            'pinged' => $this->pinged,
            'slug' => $this->slug,
            'status' => $this->status,
            'template' => $this->template,
            'thumbnail' => $this->thumbnail,
            'title' => $this->title,
            'type' => $this->type,
        ];
    }
}
