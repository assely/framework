<?php

use Assely\Routing\Router;

class RouterTest extends TestCase
{
    /**
     * @test
     */
    public function test_routes_methods_normalizer()
    {
        $route = $this->getRoute();
        $routes = $this->getRoutes();
        $conditions = $this->getConditions();
        $response = $this->getResponse();
        $container = $this->getContainer();

        $router = $this->getRouter($routes, $conditions, $response, $container);

        $normalized = $router->normalizeRequestMethods('get');
        $this->assertEquals($normalized, 'GET');

        $normalized = $router->normalizeRequestMethods(['get', 'POST', 'pUT']);
        $this->assertEquals($normalized, ['GET', 'POST', 'PUT']);
    }

    /**
     * @test
     */
    public function test_creating_routes()
    {
        $route = $this->getRoute();
        $routes = $this->getRoutes();
        $conditions = $this->getConditions();
        $response = $this->getResponse();
        $container = $this->getContainer();

        $router = $this->getRouter($routes, $conditions, $response, $container);

        $container->shouldReceive('make')->andReturn($route);
        $routes->shouldReceive('add')->with($route)->andReturn($route);

        $route->shouldReceive('setPath')->times()->with('route/path')->andReturn($route);
        $route->shouldReceive('setAction')->times()->with('Controller@method')->andReturn($route);

        $route->shouldReceive('setMethods')->once()->with(['GET'])->andReturn($route);
        $router->get('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['POST'])->andReturn($route);
        $router->post('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['HEAD'])->andReturn($route);
        $router->head('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['PUT'])->andReturn($route);
        $router->put('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['DELETE'])->andReturn($route);
        $router->delete('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['GET', 'HEAD', 'POST', 'PUT', 'DELETE'])->andReturn($route);
        $router->any('route/path', 'Controller@method');

        $route->shouldReceive('setMethods')->once()->with(['GET', 'POST'])->andReturn($route);
        $router->match(['GET', 'POST'], 'route/path', 'Controller@method');
    }

    public function getRoute()
    {
        return Mockery::mock('Assely\Routing\Route');
    }

    public function getRoutes()
    {
        return Mockery::mock('Assely\Routing\RoutesCollection');
    }

    public function getConditions()
    {
        return Mockery::mock('Assely\Routing\WordpressConditions');
    }

    public function getResponse()
    {
        return Mockery::mock('Illuminate\Http\Response');
    }

    public function getContainer()
    {
        return Mockery::mock('Illuminate\Container\Container');
    }

    public function getRouter($routes, $conditions, $response, $container)
    {
        return new Router($routes, $conditions, $response, $container);
    }
}
