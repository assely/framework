<?php

use Brain\Monkey\Functions;
use Assely\Rewrite\Endpoint;

class EndpointTest extends TestCase
{
    /**
     * @test
     */
    public function test_endpoint_adding()
    {
        $hook = $this->getHook();
        $endpoint = $this->getEndpoint($hook);

        $hook->shouldReceive('action')->with('init', [$endpoint, 'register'])->once()->andReturn($hook);
        $hook->shouldReceive('dispatch')->once()->andReturn($hook);

        $endpoint->add();
    }

    /**
     * @test
     */
    public function test_endpoint_registering()
    {
        $hook = $this->getHook();
        $endpoint = $this->getEndpoint($hook);

        $endpoint
            ->setPoint('point')
            ->to('place');

        Functions::expect('add_rewrite_endpoint')->once()->with('point', 'place');

        $endpoint->register();
    }

    /**
     * @test
     */
    public function test_getters_and_setters()
    {
        $hook = $this->getHook();
        $endpoint = $this->getEndpoint($hook);

        $endpoint
            ->setPoint('point')
            ->to('place');

        $this->assertEquals($endpoint->getPoint(), 'point');
        $this->assertEquals($endpoint->getPlace(), 'place');
        $this->assertEquals($endpoint->getSlug(), 'point');
    }

    public function getHook()
    {
        return Mockery::mock('Assely\Hook\HookFactory');
    }

    public function getEndpoint($hook)
    {
        return new Endpoint($hook);
    }
}
