<?php

namespace Assely\Foundation\Providers;

use Assely\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register route service.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot routes.
     *
     * @param \Assely\Routing\Router $router
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function boot(
        Router $router,
        Request $request
    ) {
        // Dispach router with request object.
        $router->setRequest($request->capture());

        // Set route namespace for controller creation.
        $router->setNamespace($this->namespace);

        // Add custom conditions to the router.
        $router->addConditions($this->routeConditions());

        // Map application defined routes.
        $this->load();

        // Register custom routes rewrite rules.
        $router->registerRewriteRules();
    }
}
