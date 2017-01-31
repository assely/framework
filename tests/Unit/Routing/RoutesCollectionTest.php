<?php

use Assely\Routing\RoutesCollection;

class RoutesCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_have_groups_for_each_http_method()
    {
        $collection = $this->getCollection();

        $this->assertArrayHasKey('GET', $collection->getGroups());
        $this->assertArrayHasKey('HEAD', $collection->getGroups());
        $this->assertArrayHasKey('POST', $collection->getGroups());
        $this->assertArrayHasKey('PUT', $collection->getGroups());
        $this->assertArrayHasKey('DELETE', $collection->getGroups());
    }

    /**
     * @test
     */
    public function test_routes_groups_getter()
    {
        $route = $this->getRoute('route/path/get', ['GET']);
        $collection = $this->getCollection();

        $collection->add($route);

        $this->assertEquals($collection->getGroups(), [
            'GET' => [$route->getPath() => $route],
            'HEAD' => [],
            'POST' => [],
            'PUT' => [],
            'DELETE' => [],
        ]);
    }

    /**
     * @test
     */
    public function test_routes_single_group_getter()
    {
        $route = $this->getRoute('route/path/get', ['GET']);
        $collection = $this->getCollection();

        $collection->add($route);

        $this->assertEquals($collection->getGroup('GET'), [$route->getPath() => $route]);
    }

    /**
     * @test
     */
    public function test_routes_single_group_getter_on_nonexisting_group()
    {
        $collection = $this->getCollection();

        $this->expectException('Assely\Routing\RoutingException');

        $collection->getGroup('NONEXIST');
    }

    /**
     * @test
     */
    public function test_adding_a_route_to_the_collection()
    {
        $routeGet = $this->getRoute('route/path/get', ['GET']);
        $routePost = $this->getRoute('route/path/post', ['GET']);
        $collection = $this->getCollection();

        $collection->add($routeGet);
        $collection->add($routePost);

        $this->assertContainsOnly($routeGet, $collection->getGroup('GET'));
        $this->assertContainsOnlyInstancesOf('Assely\Routing\Route', $collection->getGroup('GET'));

        $this->assertContainsOnly($routePost, $collection->getGroup('POST'));
        $this->assertContainsOnlyInstancesOf('Assely\Routing\Route', $collection->getGroup('POST'));

        $this->assertContains($routeGet, $collection->getAll());
        $this->assertContains($routePost, $collection->getAll());
        $this->assertContainsOnlyInstancesOf('Assely\Routing\Route', $collection->getAll());
    }

    /**
     * @test
     */
    public function test_route_getter()
    {
        $route = $this->getRoute('route/path/get', ['GET']);
        $collection = $this->getCollection();

        $collection->add($route);

        $this->assertEquals($collection->get('route/path/get'), $route);
    }

    /**
     * @test
     */
    public function test_route_getter_with_nonexisting_route()
    {
        $collection = $this->getCollection();

        $this->expectException('Assely\Routing\RoutingException');

        $collection->get('route/path/nonexist');
    }

    public function getRoute($path, array $methods)
    {
        $route = Mockery::mock('Assely\Routing\Route');

        $route->shouldReceive('getMethods')->andReturn($methods);
        $route->shouldReceive('getPath')->andReturn($path);

        return $route;
    }

    public function getCollection()
    {
        return new RoutesCollection;
    }
}
