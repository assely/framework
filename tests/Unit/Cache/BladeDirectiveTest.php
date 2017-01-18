<?php

use Assely\Cache\BladeDirective;
use Assely\Cache\Cache;

class BladeDirectiveTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_push_key_to_keys_collection_on_set_up()
    {
        $cache = $this->getCache();
        $directive = $this->getDirective($cache);

        $cache->shouldReceive('has')->once()->andReturn(false);

        $directive->setUp('key');

        $this->assertEquals($directive->getKeys(), ['key']);

        ob_get_clean();
    }

    /**
     * @test
     */
    public function it_should_pop_key_from_keys_collection_on_tear_down()
    {
        ob_start();

        $cache = $this->getCache();
        $directive = $this->getDirective($cache);

        $directive->setKeys(['key1', 'key2']);

        $cache->shouldReceive('put')->once()->andReturn(true);

        $directive->tearDown();

        $this->assertEquals($directive->getKeys(), ['key1']);
    }

    /**
     * @test
     */
    public function it_should_get_cache_on_tear_down_if_cached_flag_is_up()
    {
        $cache = $this->getCache();
        $directive = $this->getDirective($cache);

        $cache->shouldReceive('has')->once()->andReturn(true);
        $cache->shouldReceive('get')->once()->andReturn('cached content');

        $directive->setUp('key');
        $output = $directive->tearDown();

        $this->assertEquals($directive->isCached(), true);
        $this->assertEquals($output, 'cached content');
    }

    /**
     * @param $cache
     */
    public function getDirective($cache)
    {
        return new BladeDirective($cache);
    }

    public function getCache()
    {
        return Mockery::mock('Assely\Cache\Cache');
    }
}
