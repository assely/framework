<?php

namespace Assely\Cache;

use Assely\Support\Accessors\HasArguments;

class BladeDirective
{
    use HasArguments;

    /**
     * Cache instance.
     *
     * @var \Assely\Cache\Cache
     */
    protected $cache;

    /**
     * Cache keys heap.
     *
     * @var array
     */
    protected $keys = [];

    /**
     * Caching status flag.
     *
     * @var bool
     */
    protected $cached = false;

    /**
     * Default arguments.
     *
     * @var array
     */
    protected $defaults = [];

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
     * @return bool
     */
    public function setUp($key, $arguments = [])
    {
        // Set cache arguments.
        $this->setArguments($arguments);

        // Start output buffer.
        ob_start();

        // Store cache key.
        $this->keys[] = $key = (string) $key;

        // Return cache status.
        return $this->cached = $this->cache->has($key);
    }

    /**
     * Returns after widget markup.
     *
     * @return string
     */
    public function tearDown()
    {
        // Get and clean output buffer.
        $buffer = ob_get_clean();

        // Pop lastest cache key.
        $key = array_pop($this->keys);

        // Put output buffer content, if
        // we don't have cached value.
        if ( ! $this->cached) {
            $this->cache->put($key, $buffer, $this->getArgument('expire'));

            return $buffer;
        }

        // Return cache value.
        return $this->cache->get($key);
    }

    /**
     * Gets the value of cached flag.
     *
     * @return boolean
     */
    public function isCached()
    {
        return $this->cached;
    }

    /**
     * Gets the Cache keys heap.
     *
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Sets the Cache keys heap.
     *
     * @param array $keys the keys
     *
     * @return self
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;

        return $this;
    }
}
