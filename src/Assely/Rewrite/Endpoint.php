<?php

namespace Assely\Rewrite;

class Endpoint
{
    /**
     * Endpoint name.
     *
     * @var string
     */
    protected $point;

    /**
     * Endpoint apply places.
     *
     * @var int
     */
    protected $places;

    /**
     * Construct endpoint.
     *
     * @param string $point
     * @param int $places
     */
    public function __construct($point, $places = EP_NONE)
    {
        $this->point = $point;
        $this->places = $places;
    }

    /**
     * Register endpoint.
     *
     * @return void
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
