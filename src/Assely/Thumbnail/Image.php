<?php

namespace Assely\Thumbnail;

use JsonSerializable;

class Image implements JsonSerializable
{
    /**
     * Image id.
     *
     * @var int
     */
    public $id;

    /**
     * Image size name.
     *
     * @var string
     */
    public $size;

    /**
     * Image url.
     *
     * @var string
     */
    public $link;

    /**
     * Image title.
     *
     * @var string
     */
    public $title;

    /**
     * Image caption.
     *
     * @var string
     */
    public $caption;

    /**
     * Image description.
     *
     * @var string
     */
    public $description;

    /**
     * Image post type.
     *
     * @var string
     */
    public $type;

    /**
     * Image mime type.
     *
     * @var string
     */
    public $mime_type;

    /**
     * Image meta data.
     *
     * @var array
     */
    public $meta;

    /**
     * Image width.
     *
     * @var string
     */
    public $width;

    /**
     * Image height.
     *
     * @var string
     */
    public $height;

    /**
     * Image meta data.
     *
     * @var array
     */
    protected $data;

    /**
     * Image meta info.
     *
     * @var \WP_Post
     */
    protected $info;

    /**
     * Construct image.
     *
     * @param int $id
     * @param string $size
     */
    public function __construct($id, $size)
    {
        $this->id = $id;
        $this->size = $size;
    }

    public function link()
    {
        return $this->link = wp_get_attachment_image_src($this->id, $this->size)[0];
    }

    public function data()
    {
        return $this->data = wp_get_attachment_metadata($this->id);
    }

    public function info()
    {
        return $this->info = get_post($this->id);
    }

    public function title()
    {
        return $this->title = $this->info->post_title;
    }

    public function caption()
    {
        return $this->caption = $this->info->post_excerpt;
    }

    public function description()
    {
        return $this->description = $this->info->post_content;
    }

    public function type()
    {
        return $this->type = $this->info->post_type;
    }

    public function mime_type()
    {
        return $this->mime_type = $this->info->post_mime_type;
    }

    public function meta()
    {
        return $this->meta = $this->data['image_meta'];
    }

    public function width()
    {
        return $this->width = $this->data['width'];
    }

    public function height()
    {
        return $this->height = $this->data['height'];
    }

    /**
     * Handle json encoding.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'size' => $this->size,
            'link' => $this->link,
            'title' => $this->title,
            'caption' => $this->caption,
            'description' => $this->description,
            'type' => $this->type,
            'mimeType' => $this->mime_type,
            'meta' => $this->meta,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }

    public function __get($name)
    {
        if (!isset($this->{$name}) && method_exists($this, $name)) {
            return $this->{$name}();
        }
    }
}
