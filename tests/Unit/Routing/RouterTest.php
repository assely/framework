<?php

use Assely\Routing\Router;
use Assely\Routing\RoutesCollection;
use Brain\Monkey\Functions;
use Illuminate\Container\Container;

class RouterTest extends TestCase
{
    /**
     * @test
     */
    public function test_basic_routes_execution()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);
        $wp = new WP('route/path');
        $wp_query = new WP_Query;

        $conditions->shouldReceive('is')->with('404')->andReturn(false);

        $router->get('route/path', function () {
            return 'get text';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('get text', $router->execute($wp, $wp_query)->getContent());

        $router->post('route/path', function () {
            return 'post text';
        });
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('post text', $router->execute($wp, $wp_query)->getContent());

        $router->put('route/path', function () {
            return 'put text';
        });
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('put text', $router->execute($wp, $wp_query)->getContent());

        $router->delete('route/path', function () {
            return 'delete text';
        });
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals('delete text', $router->execute($wp, $wp_query)->getContent());

        $router->head('route/path', function () {
            return 'head text';
        });
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertEquals('head text', $router->execute($wp, $wp_query)->getContent());

        $router->any('route/path', function () {
            return 'any text';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('any text', $router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('any text', $router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('any text', $router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals('any text', $router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertEquals('any text', $router->execute($wp, $wp_query)->getContent());

        $router->match(['GET', 'POST'], 'route/path', function () {
            return 'match text';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('match text', $router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('match text', $router->execute($wp, $wp_query)->getContent());
    }

    /**
     * @test
     */
    public function test_routes_with_queries_execution()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);

        $conditions->shouldReceive('is')->with('404')->andReturn(false);
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $wp = new WP('postname');
        $wp_query = new WP_Query(['name' => 'postname']);
        $router->get('{name}', function ($name) { return $name; });
        $this->assertEquals('postname', $router->execute($wp, $wp_query)->getContent());

        $wp = new WP('rewrite/path');
        $wp_query = new WP_Query(['custom' => 'rewrite', 'rule' => 'path']);
        $router->get('{custom}/{rule}', function ($custom, $rule) { return $custom . ' ' . $rule; });
        $this->assertEquals('rewrite path', $router->execute($wp, $wp_query)->getContent());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_route_dont_exist()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);
        $wp = new WP('route/path');
        $wp_query = new WP_Query;

        $conditions->shouldReceive('is')->with('404')->andReturn(false);

        $this->expectException('Assely\Routing\RoutingException');

        $router->execute($wp, $wp_query);
    }

    /**
     * @test
     */
    public function it_should_resolve_404_route_on_not_found_error_when_route_exist()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);
        $wp = new WP('route/path');
        $wp_query = new WP_Query;

        $conditions->shouldReceive('is')->with('404')->andReturn(true);

        $router->get('404', function () {
            return 'not found';
        });

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('not found', $router->execute($wp, $wp_query)->getContent());
    }

    /**
     * @test
     */
    public function it_should_redirect_to_the_homepage_on_not_found_error_when_404_route_dont_exist()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);
        $wp = new WP('route/path');
        $wp_query = new WP_Query;

        $conditions->shouldReceive('is')->with('404')->andReturn(true);

        Functions::expect('home_url')->once()->andReturn('home/url');
        Functions::expect('wp_redirect')->with('home/url')->andReturn(false);

        $router->execute($wp, $wp_query);
    }

    /**
     * @test
     */
    public function test_routes_methods_normalizer()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);

        $normalized = $router->normalizeRequestMethods('get');
        $this->assertEquals($normalized, 'GET');

        $normalized = $router->normalizeRequestMethods(['get', 'POST', 'pUT']);
        $this->assertEquals($normalized, ['GET', 'POST', 'PUT']);
    }

    /**
     * @test
     */
    public function test_namespace_setter_and_getter()
    {
        $conditions = $this->getConditions();
        $router = $this->getRouter($conditions);

        $router->setNamespace('App\Namespace');

        $this->assertEquals('App\Namespace', $router->getNamespace());
    }

    public function getConditions()
    {
        return Mockery::mock('Assely\Routing\WordpressConditions');
    }

    public function getRouter($conditions)
    {
        return new Router(
            new RoutesCollection,
            $conditions,
            new Container
        );
    }
}

class WP
{
    public function __construct($request = '')
    {
        $this->request = $request;
    }
}

class WP_Query
{
    public function __construct($query_vars = [])
    {
        $this->query_vars = $query_vars;
    }
}