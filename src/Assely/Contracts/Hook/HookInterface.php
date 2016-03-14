<?php

namespace Assely\Contracts\Hook;

interface HookInterface
{
    /**
     * Dispach hook.
     */
    public function dispatch();

    /**
     * Perform hook.
     *
     * @param mixed $parameters
     */
    public function perform($parameters);

    /**
     * Execute hook.
     *
     * @param string $action
     * @param array $parameters
     */
    public function execute($action, $parameters);
}
