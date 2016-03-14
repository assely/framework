<?php

namespace Assely\Singularity\Model;

use Assely\Contracts\Singularity\PreservesMetaInterface;
use Assely\Singularity\Model;
use Assely\Singularity\PreservesMeta;

class MetaboxModel extends Model implements PreservesMetaInterface
{
    use PreservesMeta;

    /**
     * Model context.
     *
     * @var string
     */
    protected $context = 'post';

    /**
     * Default metabox params.
     *
     * @var array
     */
    protected $defaults = [
        // Wordpress specific
        'location' => 'normal',
        'priority' => 'default',

        // Assely specific
        'title' => [],
        'description' => '',
        'preserve' => 'single',
    ];

    /**
     * Read post meta from Singularity.
     *
     * @param  array  $parameters
     *
     * @return array
     */
    public function readMeta(array $parameters)
    {
        return call_user_func_array("get_{$this->getContext()}_meta", $parameters);
    }

    /**
     * Save post meta to Singularity.
     *
     * @param  array  $parameters
     *
     * @return boolean|integer
     */
    public function makeMeta(array $parameters)
    {
        return call_user_func_array("add_{$this->getContext()}_meta", $parameters);
    }

    /**
     * Save post meta to Singularity.
     *
     * @param  array  $parameters
     *
     * @return boolean|integer
     */
    public function saveMeta(array $parameters)
    {
        return call_user_func_array("update_{$this->getContext()}_meta", $parameters);
    }

    /**
     * Remove post meta form Singularity,
     *
     * @param  array  $parameters
     *
     * @return boolean
     */
    public function removeMeta(array $parameters)
    {
        return call_user_func_array("delete_{$this->getContext()}_meta", $parameters);
    }

    /**
     * Gets the value of context.
     *
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Sets the value of context.
     *
     * @param mixed $context the context
     *
     * @return self
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }
}
