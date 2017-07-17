<?php

use Assely\Routing\Route;
use Illuminate\Container\Container;

class RouteTest extends TestCase
{
    /**
     * @test
     */
    public function test_getters_and_setters()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

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
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->setPath('/route/path');
        $this->assertEquals($route->getPath(), 'route/path');

        $route->setPath('/route/path/');
        $this->assertEquals($route->getPath(), 'route/path');

        $route->setPath('/route/{path}');
        $this->assertEquals($route->getPath(), 'route/{path}');

        $route->setPath('/route/{path}/');
        $this->assertEquals($route->getPath(), 'route/{path}');

        $route->setPath('/{path}');
        $this->assertEquals($route->getPath(), '{path}');
    }

    /**
     * @test
     */
    public function test_home_path_positive_check()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

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
    public function test_home_path_negative_check()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->setPath('route/path');
        $this->assertFalse($route->isHomePath());

        $route->setPath('/route/path');
        $this->assertFalse($route->isHomePath());

        $route->setPath('/route/path/');
        $this->assertFalse($route->isHomePath());

        $route->setPath('{name}');
        $this->assertFalse($route->isHomePath());

        $route->setPath('/{name}');
        $this->assertFalse($route->isHomePath());
    }

    /**
     * @test
     */
    public function test_path_mocking_with_queries()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->setPath('route/{path}/{deep}/with/{query}');

        $mock = $route->getPathMock([
            'path' => ['path_value'],
            'deep' => [
                'value' => [
                    'deep_path' => 'deep_value',
                ],
            ],
            'query' => 'query_value',
        ]);

        $this->assertEquals('route/path_value/deep_value/with/query_value', $mock);
    }

    /**
     * @test
     */
    public function test_passing_rules_evaluation()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->where([
            'page' => 1,
            'post' => [2, 3],
        ]);

        $conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(true);
        $conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertFalse($route->rulesNotPassed());
    }

    /**
     * @test
     */
    public function test_not_passing_rules_evaluation()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->where([
            'page' => 1,
            'post' => [2, 3],
        ]);

        $conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);
        $conditions->shouldReceive('is')->once()->with('post', [2, 3])->andReturn(true);

        $this->assertTrue($route->rulesNotPassed());
    }

    /**
     * @test
     */
    public function test_route_request_matching_without_rules()
    {
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

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
        $conditions = $this->getConditions();
        $route = $this->getRoute($conditions);

        $route->setPath('route/{query}')->where(['page' => 1]);

        $conditions->shouldReceive('is')->once()->with('page', 1)->andReturn(false);

        $this->assertFalse($route->matches('route/query_value', ['query' => 'query_value']));
    }

    public function getConditions()
    {
        return Mockery::mock('Assely\Routing\WordpressConditions');
    }

    public function getRoute($conditions)
    {
        return new Route(
            $conditions,
            new Container
        );
    }
}
