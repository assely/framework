<?php

namespace Assely\Cache;

use Assely\Config\ApplicationConfig;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasArguments;

class Cache
{
    use HasSlug, HasArguments;

    /**
     * Cache defaults arguments.
     *
     * @var array
     */
    private $defaults = [];

    /**
     * Construct cache.
     *
     * @param \Assely\Config\ApplicationConfig $config
     */
    public function __construct(ApplicationConfig $config)
    {
        $this->setDefaults($config->get('cache'));
    }

    /**
     * Put data to store.
     *
     * @return bool
     */
    public function put($key, $value, $expire = null)
    {
        return call_user_func_array("set{$this->getPrefix()}_transient", [
            $key,
            $value,
            $expire ?: $this->getArgument('expire'),
        ]);
    }

    /**
     * Get data from store.
     *
     * @return bool
     */
    public function get($key)
    {
        return call_user_func("get{$this->getPrefix()}_transient", $key);
    }

    /**
     * Cache has data.
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) !== false;
    }

    /**
     * Delete data form store.
     *
     * @return bool
     */
    public function flush($key)
    {
        return call_user_func("delete{$this->getPrefix()}_transient", $key);
    }

    /**
     * Get store functions prefix. Returns "_site"
     * prefix if transient is for multisites.
     *
     * @return string
     */
    public function getPrefix()
    {
        if ($this->getArgument('multisite')) {
            return '_site';
        }
    }
}
