<?php

namespace Assely\Foundation\Providers;

use Assely\Routing\Router;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Boot routes.
     *
     * @param \Assely\Routing\Router $router
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // Set route namespace for controller creation.
        $router->setNamespace($this->getNamespace());

        // Map application defined routes.
        $this->load();
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
