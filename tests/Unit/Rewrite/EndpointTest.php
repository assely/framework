<?php

use Assely\Rewrite\Endpoint;
use Brain\Monkey\Functions;

class EndpointTest extends TestCase
{
    /**
     * @test
     */
    public function test_endpoint_adding()
    {
        $endpoint = new Endpoint('point', 1);

        Functions::expect('add_rewrite_endpoint')->once()->andReturn(null);

        $endpoint->add();
    }

    /**
     * @test
     */
    public function test_slug_getter()
    {
        $endpoint = new Endpoint('point', 1);

        $this->assertEquals($endpoint->getSlug(), 'point');
    }
}
