<?php

namespace Assely\Cache;

use Assely\Adapter\Adapter;
use Assely\Support\Accessors\Arguments;

class BladeDirective
{
    use Arguments;

    /**
     * Cache instance.
     *
     * @var \Assely\Cache\Cache
     */
    protected $cache;

    /**
     * Construct directive.
     *
     * @param \Assely\Cache\CacheFactory $factory
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Returns before widget markup.
     *
     * @param \Assely\Adapter\Adapter|string $key
     * @param array $arguments
     *
     * @return boolean
     */
    public function setUp($key, $arguments = [])
    {
        // Don't process if debug mode is turn on.
        if (WP_DEBUG) {
            return;
        }

        // Start output buffer.
        ob_start();

        // Set cache arguments.
        $this->setArguments($arguments);

        // Store cache key.
        $this->keys[] = (string) $key;

        // Return cache status.
        return $this->isCached = $this->cache->has(end($this->keys));
    }

    /**
     * Returns after widget markup.
     *
     * @return string
     */
    public function tearDown()
    {
        // Don't process if debug mode is turn on.
        if (WP_DEBUG) {
            return;
        }

        // Pop lastest cache key.
        $key = array_pop($this->keys);

        // Put output buffer content, if
        // we don't have cached value.
        if (! $this->isCached) {
            $this->cache->put($key, ob_get_clean(), $this->getArgument('expire'));
        }

        // Return cache value.
        return $this->cache->get($key);
    }
}
