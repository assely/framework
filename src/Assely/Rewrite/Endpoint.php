<?php

namespace Assely\Rewrite;

class Endpoint
{
    /**
     * @var mixed
     */
    protected $point;

    /**
     * @var mixed
     */
    protected $places;

    /**
     * @param $point
     * @param $places
     */
    public function __construct($point, $places = EP_NONE)
    {
        $this->point = $point;
        $this->places = $places;
    }

    /**
     * @param $point
     * @param $places
     * @return mixed
     */
    public function add()
    {
        return add_rewrite_endpoint($this->point, $this->places);
    }

    /**
     * Gets the Endpoint slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->point;
    }
}
