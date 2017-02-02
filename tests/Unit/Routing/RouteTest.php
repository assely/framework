<?php

use Assely\Routing\Route;

class RouteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->router = $this->getRouter();
        $this->conditions = $this->getConditions();
        $this->hook = $this->getHook();
        $this->container = $this->getContainer();
    }

    /**
     * @test
     */
    public function test_getters_and_setters()
    {
        $route = $this->getRoute();

        $route->where(['query', 'rule']);
        $route->setMethods(['GET', 'POST']);
        $route->setPath('route/path');
        $route->setAction('Controller@method');
        $route->setQueries(['query' => 'name']);

        $this->assertEquals($route->getMethods(), ['GET', 'POST']);
        $this->assertEquals($route->getPath(), 'route/path');
        $this->assertEquals($route->getAction(), 'Controller@method');
        $this->assertEquals($route->getQueries(), ['query' => 'name']);
        $this->assertEquals($route->getRules(), ['query', 'rule']);
    }

    /**
     * @test
     */
    public function it_should_trim_trailing_slashes_on_path_setter()
    {
        $route = $this->getRoute();

        $route->setPath('/route/path');
        $this->assertEquals($route->getPath(), 'route/path');

        $route->setPath('/route/path/');
        $this->assertEquals($route->getPath(), 'route/path');

        $route->setPath('/route/{path}');
        $this->assertEquals($route->getPath(), 'route/{path}');

        $route->setPath('/route/{path}/');
        $this->assertEquals($route->getPath(), 'route/{path}');
    }

    /**
     * @test
     */
    public function test_home_path_positive_check()
    {
        $route = $this->getRoute();

        $route->setPath('');
        $this->assertTrue($route->isHomePath());

        $route->setPath('/');
        $this->assertTrue($route->isHomePath());

        $route->setPath('//');
        $this->assertTrue($route->isHomePath());

        $route->setPath('///');
        $this->assertTrue($route->isHomePath());
    }

    /**
     * @test
     */
    public function test_path_mocking_with_queries()
    {
        $route = $this->getRoute();

        $route->setPath('route/{path}/with/{query}');

        $mock = $route->getPathMock([
            'path' => ['path_value'],
            'query' => 'query_value'
        ]);

        $this->assertEquals('route/path_value/with/query_value', $mock);
    }

    /**
     * @test
     */
    public function test_all_passing_rules_evaluation()
    {
        $route = $this->getRoute();

        $route->where([
            'page' => 1,
            'post' => [2, 3]
        ]);

        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(true);
        $this->conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertFalse($route->rulesNotPassed());
        $this->assertEquals(['page' => true, 'post' => true], $route->getRules());
    }

    /**
     * @test
     */
    public function test_not_passing_rules_evaluation()
    {
        $route = $this->getRoute();

        $route->where([
            'page' => 1,
            'post' => [2, 3]
        ]);

        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);
        $this->conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertTrue($route->rulesNotPassed());
        $this->assertEquals(['page' => false, 'post' => true], $route->getRules());
    }

    /**
     * @test
     */
    public function test_route_request_matching_without_rules()
    {
        $route = $this->getRoute();

        $route->setPath('/');
        $this->assertTrue($route->matches(''));
        $this->assertFalse($route->matches('route/path'));

        $route->setPath('route/path');
        $this->assertTrue($route->matches('route/path'));
        $this->assertFalse($route->matches('route/path/deeper'));

        $route->setPath('route/{query}');
        $this->assertTrue($route->matches('route/query_value', ['query' => 'query_value']));
        $this->assertFalse($route->matches('route/{query_value}/deeper', ['query' => 'query_value']));
    }

    /**
     * @test
     */
    public function test_route_request_matching_with_rules()
    {
        $route = $this->getRoute();

        $route->setPath('route/{query}')->where(['page' => 1]);
        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);
        $this->assertFalse($route->matches('route/query_value', ['query' => 'query_value']));
    }

    /**
     * @test
     */
    public function test_home_path_negative_check()
    {
        $route = $this->getRoute();

        $route->setPath('route/path');
        $this->assertFalse($route->isHomePath());

        $route->setPath('/route/path');
        $this->assertFalse($route->isHomePath());

        $route->setPath('/route/path/');
        $this->assertFalse($route->isHomePath());
    }

    public function getRouter()
    {
        return Mockery::mock('Assely\Routing\Router');
    }

    public function getConditions()
    {
        return Mockery::mock('Assely\Routing\WordpressConditions');
    }

    public function getHook()
    {
        return Mockery::mock('Assely\Hook\HookFactory');
    }

    public function getContainer()
    {
        return Mockery::mock('Illuminate\Container\Container');
    }

    public function getRoute()
    {
        return new Route(
            $this->router,
            $this->conditions,
            $this->hook,
            $this->container
        );
    }
}
