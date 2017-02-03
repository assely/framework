<?php

use Assely\Routing\Router;
use Assely\Routing\RoutesCollection;
use Assely\Routing\WordpressConditions;
use Brain\Monkey\Functions;
use Illuminate\Container\Container;

class RouterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->conditions = Mockery::mock(WordpressConditions::class);

        $this->router = new Router(
            new RoutesCollection,
            $this->conditions,
            new Container
        );
    }

    /**
     * @test
     */
    public function test_basic_routes_execution()
    {
        $wp = new WP('route/path');
        $wp_query = new WP_Query;

        $this->conditions->shouldReceive('is')->with('404')->andReturn(false);

        $this->router->get('route/path', function () { return 'get text'; });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('get text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->post('route/path', function () { return 'post text'; });
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('post text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->put('route/path', function () { return 'put text'; });
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('put text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->delete('route/path', function () { return 'delete text'; });
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals('delete text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->head('route/path', function () { return 'head text'; });
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertEquals('head text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->any('route/path', function () { return 'any text'; });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('any text', $this->router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('any text', $this->router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('any text', $this->router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals('any text', $this->router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertEquals('any text', $this->router->execute($wp, $wp_query)->getContent());

        $this->router->match(['GET', 'POST'], 'route/path', function () { return 'match text'; });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('match text', $this->router->execute($wp, $wp_query)->getContent());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('match text', $this->router->execute($wp, $wp_query)->getContent());
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
    public function test_namespace_setter_and_getter()
    {
        $this->router->setNamespace('App\Namespace');

        $this->assertEquals('App\Namespace', $this->router->getNamespace());
    }
}