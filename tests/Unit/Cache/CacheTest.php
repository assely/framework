<?php

use Assely\Cache\Cache;
use Assely\Config\ApplicationConfig;
use Brain\Monkey\Functions;

class CacheTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_populate_defaults_with_application_config()
    {
        $cache = $this->getCache();

        $this->assertEquals($cache->getDefaults(), [
            'expire' => '1000',
            'multisite' => false,
        ]);

        $this->assertEquals($cache->getDefault('expire'), '1000');
        $this->assertEquals($cache->getDefault('multisite'), false);
    }

    /**
     * @test
     */
    public function it_should_defines_multisite_prefix_constans()
    {
        $this->assertEquals(Cache::MULTISITE_PREFIX, '_site');
    }

    /**
     * @test
     */
    public function it_should_set_transient_on_put()
    {
        $cache = $this->getCache();

        Functions::expect('set_transient')->once()->andReturn(true);

        $cache->put('key', 'value');
    }

    /**
     * @test
     */
    public function it_should_get_transient_on_get_or_has()
    {
        $cache = $this->getCache();

        Functions::expect('get_transient')->twice()->andReturn(true);

        $cache->has('key');
        $cache->get('key');
    }

    /**
     * @test
     */
    public function it_should_delete_transient_on_flush()
    {
        $cache = $this->getCache();

        Functions::expect('delete_transient')->once()->andReturn(true);

        $cache->flush('key');
    }

    public function getCache($arguments = [])
    {
        $config = new ApplicationConfig([
            'cache' => array_merge([
                'expire' => '1000',
                'multisite' => false,
            ], $arguments)
        ]);

        return new Cache($config);
    }
}
