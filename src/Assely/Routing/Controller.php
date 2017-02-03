<?php

namespace Assely\Routing;

use Illuminate\Http\Request;

class Controller
{
    /**
     * Request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Missing method on the controller.
     *
     * @param string $method
     *
     * @throws \Assely\Routing\RoutingException
     *
     * @return void
     */
    public function missingMethod($method)
    {
        throw new RoutingException("Controller method [{$method}] not found.");
    }

    /**
     * Sets the Request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
