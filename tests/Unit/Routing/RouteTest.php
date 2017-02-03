<?php

use Assely\Routing\Route;
use Illuminate\Container\Container;

class RouteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->conditions = $this->getConditions();
        $this->route = $this->getRoute($this->conditions);
    }

    /**
     * @test
     */
    public function test_getters_and_setters()
    {
        $this->route->where(['query', 'rule']);
        $this->route->setMethods(['GET', 'POST']);
        $this->route->setPath('route/path');
        $this->route->setAction('Controller@method');
        $this->route->setQueries(['query' => 'name']);

        $this->assertEquals($this->route->getMethods(), ['GET', 'POST']);
        $this->assertEquals($this->route->getPath(), 'route/path');
        $this->assertEquals($this->route->getAction(), 'Controller@method');
        $this->assertEquals($this->route->getQueries(), ['query' => 'name']);
        $this->assertEquals($this->route->getRules(), ['query', 'rule']);
    }

    /**
     * @test
     */
    public function it_should_trim_trailing_slashes_on_path_setter()
    {
        $this->route->setPath('/route/path');
        $this->assertEquals($this->route->getPath(), 'route/path');

        $this->route->setPath('/route/path/');
        $this->assertEquals($this->route->getPath(), 'route/path');

        $this->route->setPath('/route/{path}');
        $this->assertEquals($this->route->getPath(), 'route/{path}');

        $this->route->setPath('/route/{path}/');
        $this->assertEquals($this->route->getPath(), 'route/{path}');

        $this->route->setPath('/{path}');
        $this->assertEquals($this->route->getPath(), '{path}');
    }

    /**
     * @test
     */
    public function test_home_path_positive_check()
    {
        $this->route->setPath('');
        $this->assertTrue($this->route->isHomePath());

        $this->route->setPath('/');
        $this->assertTrue($this->route->isHomePath());

        $this->route->setPath('//');
        $this->assertTrue($this->route->isHomePath());

        $this->route->setPath('///');
        $this->assertTrue($this->route->isHomePath());
    }

    /**
     * @test
     */
    public function test_home_path_negative_check()
    {
        $this->route->setPath('route/path');
        $this->assertFalse($this->route->isHomePath());

        $this->route->setPath('/route/path');
        $this->assertFalse($this->route->isHomePath());

        $this->route->setPath('/route/path/');
        $this->assertFalse($this->route->isHomePath());

        $this->route->setPath('{name}');
        $this->assertFalse($this->route->isHomePath());

        $this->route->setPath('/{name}');
        $this->assertFalse($this->route->isHomePath());
    }

    /**
     * @test
     */
    public function test_path_mocking_with_queries()
    {
        $this->route->setPath('route/{path}/with/{query}');

        $mock = $this->route->getPathMock([
            'path' => ['path_value'],
            'query' => 'query_value',
        ]);

        $this->assertEquals('route/path_value/with/query_value', $mock);
    }

    /**
     * @test
     */
    public function test_passing_rules_evaluation()
    {
        $this->route->where([
            'page' => 1,
            'post' => [2, 3],
        ]);

        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(true);
        $this->conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertFalse($this->route->rulesNotPassed());
    }

    /**
     * @test
     */
    public function test_not_passing_rules_evaluation()
    {
        $this->route->where([
            'page' => 1,
            'post' => [2, 3],
        ]);

        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);
        $this->conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertTrue($this->route->rulesNotPassed());
    }

    /**
     * @test
     */
    public function test_route_request_matching_without_rules()
    {
        $this->route->setPath('/');
        $this->assertTrue($this->route->matches(''));
        $this->assertFalse($this->route->matches('route/path'));

        $this->route->setPath('route/path');
        $this->assertTrue($this->route->matches('route/path'));
        $this->assertFalse($this->route->matches('route/path/deeper'));

        $this->route->setPath('route/{query}');
        $this->assertTrue($this->route->matches('route/query_value', ['query' => 'query_value']));
        $this->assertFalse($this->route->matches('route/{query_value}/deeper', ['query' => 'query_value']));
    }

    /**
     * @test
     */
    public function test_route_request_matching_with_rules()
    {
        $this->route->setPath('route/{query}')->where(['page' => 1]);

        $this->conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);

        $this->assertFalse($this->route->matches('route/query_value', ['query' => 'query_value']));
    }

    public function getConditions()
    {
        return Mockery::mock('Assely\Routing\WordpressConditions');
    }

    public function getHook()
    {
        return Mockery::mock('Assely\Hook\HookFactory');
    }

    public function getRoute($conditions)
    {
        return new Route(
            $conditions,
            new Container
        );
    }
}
