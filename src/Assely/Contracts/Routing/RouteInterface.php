<?php

namespace Assely\Contracts\Routing;

interface RouteInterface
{
    /**
     * Run route.
     *
     * @return mixed
     */
    public function run();

    /**
     * Run controller method action.
     *
     * @return mixed
     */
    public function runController();

    /**
     * Call callable router action.
     *
     * @return mixed
     */
    public function runCallable();
}
