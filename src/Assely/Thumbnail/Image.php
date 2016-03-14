<?php

namespace Assely\Thumbnail;

use JsonSerializable;

class Image implements JsonSerializable
{
    /**
     * Image id.
     *
     * @var integer
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
     * @param integer $id
     * @param string $size
     */
    public function __construct($id, $size)
    {
        $this->id = $id;
        $this->size = $size;

        $this->link = $this->getLink();
        $this->data = $this->getData();
        $this->info = $this->getInfo();

        $this->resolveInfoMeta();
        $this->resolveDataMeta();
    }

    /**
     * Resolve image info.
     *
     * @return void
     */
    public function resolveInfoMeta()
    {
        $this->title = $this->info->post_title;
        $this->caption = $this->info->post_excerpt;
        $this->description = $this->info->post_content;
        $this->type = $this->info->post_type;
        $this->mime_type = $this->info->post_mime_type;
    }

    /**
     * Resolve image meta data.
     *
     * @return void
     */
    public function resolveDataMeta()
    {
        $this->meta = $this->data['image_meta'];
        $this->width = $this->data['width'];
        $this->height = $this->data['height'];
    }

    /**
     * Gets image url.
     *
     * @return string
     */
    public function getLink()
    {
        return wp_get_attachment_image_src($this->id, $this->size)[0];
    }

    /**
     * Gets image meta data.
     *
     * @return array
     */
    protected function getData()
    {
        return wp_get_attachment_metadata($this->id);
    }

    /**
     * Gets image meta info.
     *
     * @return \WP_Post
     */
    protected function getInfo()
    {
        return get_post($this->id);
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
}
