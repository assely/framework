<?php

namespace Assely\Ajax;

use Assely\Routing\Router;
use Illuminate\Contracts\Container\Container;

class Dispatcher
{
    /**
     * Collection of actions.
     *
     * @var \Assely\Ajax\ActionsCollection
     */
    protected $actions;

    /**
     * Container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Construct ajax dispatcher.
     *
     * @param \Assely\Ajax\ActionsCollection $actions
     * @param \Illuminate\Contracts\Container\Container $app
     */
    public function __construct(
        ActionsCollection $actions,
        Router $router,
        Container $container
    ) {
        $this->actions = $actions;
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * Listen for action.
     *
     * @param string $slug
     * @param string|callable $action
     * @param array $arguments
     *
     * @return \Assely\Ajax\Ajax
     */
    public function listen($slug, $action, array $arguments = [])
    {
        $ajax = $this->container->make(Ajax::class)
            ->setSlug($slug)
            ->setAction($action)
            ->setArguments($arguments)
            ->dispatch();

        return $this->actions->set($ajax->getSlug(), $ajax);
    }
}
