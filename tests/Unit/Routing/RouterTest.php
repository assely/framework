<?php

use Assely\Routing\Router;
use Brain\Monkey\Functions;

class RouterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->route = $this->getRoute();
        $this->routes = $this->getRoutes();
        $this->conditions = $this->getConditions();
        $this->response = $this->getResponse();
        $this->container = $this->getContainer();

        $this->router = $this->getRouter(
            $this->routes,
            $this->conditions,
            $this->response,
            $this->container
        );
    }

    /**
     * @test
     */
    public function test_namespace_setter_and_getter()
    {
        $this->router->setNamespace('App\Namespace');

        $this->assertEquals('App\Namespace', $this->router->getNamespace());
    }

    /**
     * @test
     */
    public function test_routes_methods_normalizer()
    {
        $normalized = $this->router->normalizeRequestMethods('get');
        $this->assertEquals($normalized, 'GET');

        $normalized = $this->router->normalizeRequestMethods(['get', 'POST', 'pUT']);
        $this->assertEquals($normalized, ['GET', 'POST', 'PUT']);
    }

    /**
     * @test
     */
    public function test_404_route_resolving_when_it_exsist()
    {
        $this->routes->shouldReceive('get')->with('404')->andReturn($this->route);
        $this->route->shouldReceive('run');

        $this->router->resolveNotFoundRoute();
    }

    /**
     * @test
     */
    public function it_should_redirect_to_the_home_page_when_404_route_dont_exist()
    {
        $this->routes->shouldReceive('get')->with('404')->andReturn(false)->andThrow('Assely\Routing\RoutingException');

        Functions::expect('home_url')->once()->andReturn('home/url');
        Functions::expect('wp_redirect')->with('home/url')->andReturn(false);

        $this->router->resolveNotFoundRoute();
    }

    /**
     * @test
     */
    public function test_creating_routes()
    {
        $this->container->shouldReceive('make')->andReturn($this->route);
        $this->routes->shouldReceive('add')->with($this->route)->andReturn($this->route);

        $this->route->shouldReceive('setPath')->with('route/path')->andReturn($this->route);
        $this->route->shouldReceive('setAction')->with('Controller@method')->andReturn($this->route);

        $this->route->shouldReceive('setMethods')->once()->with(['GET'])->andReturn($this->route);
        $this->router->get('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['POST'])->andReturn($this->route);
        $this->router->post('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['HEAD'])->andReturn($this->route);
        $this->router->head('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['PUT'])->andReturn($this->route);
        $this->router->put('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['DELETE'])->andReturn($this->route);
        $this->router->delete('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['GET', 'HEAD', 'POST', 'PUT', 'DELETE'])->andReturn($this->route);
        $this->router->any('route/path', 'Controller@method');

        $this->route->shouldReceive('setMethods')->once()->with(['GET', 'POST'])->andReturn($this->route);
        $this->router->match(['GET', 'POST'], 'route/path', 'Controller@method');
    }

    /**
     * @test
     */
    public function it_should_resolve_404_route_with_not_found_condition_on_execution()
    {
        $this->conditions->shouldReceive('is')->once()->with('404')->andReturn(true);
        $this->routes->shouldReceive('get')->once()->with('404')->andReturn($this->route);
        $this->route->shouldReceive('run')->once()->andReturn('content');

        $this->response->shouldReceive('setContent')->once()->with('content');
        $this->response->shouldReceive('setStatusCode')->once()->with(404);
        $this->response->shouldReceive('send')->once();

        $this->router->execute(new WP, new WP_Query);
    }

    /**
     * @test
     */
    public function it_should_throw_when_no_matching_route_on_execution()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $this->expectException('Assely\Routing\RoutingException');

        $this->conditions->shouldReceive('is')->once()->with('404')->andReturn(false);
        $this->routes->shouldReceive('getGroup')->once()->with('GET')->andReturn([$this->route]);
        $this->route->shouldReceive('matches')->once()->andReturn(false);

        $this->router->execute(new WP, new WP_Query);
    }

    /**
     * @test
     */
    public function it_should_run_route_on_match_and_prepare_response()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $route1 = Mockery::mock('Assely\Routing\Route');
        $route2 = Mockery::mock('Assely\Routing\Route');

        $this->conditions->shouldReceive('is')->once()->with('404')->andReturn(false);
        $this->routes->shouldReceive('getGroup')->once()->with('GET')->andReturn([$route1, $route2]);
        $route1->shouldReceive('matches')->once()->andReturn(false);
        $route2->shouldReceive('matches')->once()->andReturn(true);

        $route2->shouldReceive('run')->once()->andReturn('content');

        $this->response->shouldReceive('setContent')->once()->with('content');
        $this->response->shouldReceive('setStatusCode')->never();
        $this->response->shouldReceive('send')->once();

        $this->router->execute(new WP, new WP_Query);
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
