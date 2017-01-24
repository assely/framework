<?php

namespace Assely\Foundation\Providers;

use Assely\Routing\Router;
use Assely\Hook\HookFactory;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Boot routes and execute route.
     *
     * @param \Assely\Hook\HookFactory $hook
     * @param \Assely\Routing\Router $router
     *
     * @return void
     */
    public function boot(
        HookFactory $hook,
        Router $router
    ) {
        // Load application defined routes.
        $this->load();

        // We going to run application router before
        // WordPress default template matching.
        $hook->filter('template_include', function () use ($router) {
            $router
                ->setNamespace($this->getNamespace())
                ->execute();

            return false;
        })->dispatch();
    }

    /**
     * Gets value of the namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
