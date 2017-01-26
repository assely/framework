<?php

use Assely\Rewrite\RewriteFactory;
use Illuminate\Container\Container;

class RewriteFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function test_rewrite_rule_creation()
    {
        $collection = $this->getCollection();
        $container = $this->getContainer();
        $rewrite = $this->getRewrite();
        $factory = $this->getFactory($collection, $container);

        $container->shouldReceive('make')->once()->andReturn($rewrite);
        $rewrite->shouldReceive('setPattern')->once()->with('rule/pattern')->andReturn($rewrite);
        $rewrite->shouldReceive('getSlug')->once()->andReturn('rule/pattern');
        $collection->shouldReceive('set')->once()->with('rule/pattern', $rewrite)->andReturn($rewrite);

        $output = $factory->rule('rule/pattern');

        $this->assertEquals($output, $rewrite);
    }

    /**
     * @test
     */
    public function test_single_endpoint_creation()
    {
        $collection = $this->getCollection();
        $container = $this->getContainer();
        $endpoint = $this->getEndpoint();
        $factory = $this->getFactory($collection, $container);

        $container->shouldReceive('make')->once()->andReturn($endpoint);
        $endpoint->shouldReceive('add')->once()->andReturn(null);
        $collection->shouldReceive('set')->once()->with('point', $endpoint)->andReturn($endpoint);

        $output = $factory->endpoint('point', 1);

        $this->assertEquals($output, $endpoint);
    }

    /**
     * @test
     */
    public function test_multiple_endpoint_creation()
    {
        $collection = $this->getCollection();
        $container = $this->getContainer();
        $endpoint = $this->getEndpoint();
        $factory = $this->getFactory($collection, $container);

        $container->shouldReceive('make')->twice()->andReturn($endpoint);
        $endpoint->shouldReceive('add')->twice()->andReturn(null);
        $collection->shouldReceive('set')->twice()->with('point', $endpoint)->andReturn($endpoint);

        $factory->endpoint([
            'point1' => 1,
            'point2' => 2,
        ]);
    }

    public function getRewrite()
    {
        return Mockery::mock('Assely\Rewrite\Rewrite');
    }

    public function getEndpoint()
    {
        return Mockery::mock('Assely\Rewrite\Endpoint[add]', ['point', 1]);
    }

    public function getCollection()
    {
        return Mockery::mock('Assely\Rewrite\RewritesCollection');
    }

    public function getContainer()
    {
        return Mockery::mock('Illuminate\Container\Container');
    }

    /**
     * @param $collection
     * @param $container
     */
    public function getFactory($collection, $container)
    {
        return new RewriteFactory($collection, $container);
    }
}
