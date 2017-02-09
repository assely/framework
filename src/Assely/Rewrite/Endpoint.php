<?php

namespace Assely\Rewrite;

use Assely\Hook\HookFactory;

class Endpoint
{
    /**
     * Endpoint name.
     *
     * @var string
     */
    protected $point;

    /**
     * Endpoint apply place.
     *
     * @var string
     */
    protected $place;

    /**
     * Construct endpoint.
     *
     * @param \Assely\Hook\HookFactory $hook
     */
    public function __construct(HookFactory $hook)
    {
        $this->hook = $hook;
    }

    /**
     * Adds endpoint.
     *
     * @return void
     */
    public function add()
    {
        $this->hook->action('init', [$this, 'register'])->dispatch();
    }

    /**
     * Registers endpoint.
     *
     * @return void
     */
    public function register()
    {
        return add_rewrite_endpoint($this->point, $this->place);
    }

    /**
     * Sets the Endpoint apply place.
     *
     * @param int $place the place
     *
     * @return self
     */
    public function to($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Gets the Endpoint apply place.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Gets the Endpoint name.
     *
     * @return string
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Sets the Endpoint name.
     *
     * @param string $point the point
     *
     * @return self
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Gets the Endpoint slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getPoint();
    }
}
