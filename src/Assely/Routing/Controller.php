<?php

namespace Assely\Routing;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller
{
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
        throw new NotFoundHttpException("Controller method [{$method}] not found.");
    }
}
