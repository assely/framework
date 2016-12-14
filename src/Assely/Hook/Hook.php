<?php

namespace Assely\Hook;

use Assely\Support\Accessors\HasSlug;
use Assely\Contracts\Hook\HookInterface;
use Assely\Support\Accessors\HasArguments;

class Hook implements HookInterface
{
    use HasSlug, HasArguments;

    /**
     * Default hook arguments.
     *
     * @var array
     */
    private $defaults = [
        'piority' => 10,
        'numberOfArguments' => 2,
    ];

    /**
     * Hook type.
     *
     * @var string
     */
    private $type;

    /**
     * Hook callback function.
     *
     * @var mixed
     */
    private $callback;

    /**
     * Hook actions names.
     *
     * @var array
     */
    private $aliases = [
        'action' => [
            'dispatch' => 'add',
            'perform' => 'do',
            'detach' => 'remove',
        ],
        'filter' => [
            'dispatch' => 'add',
            'perform' => 'apply',
            'detach' => 'remove',
        ],
    ];

    /**
     * Construct hook.
     *
     * @param string $type
     * @param string $slug
     * @param mixed $callback
     * @param array $arguments
     */
    public function __construct($type, $slug, $callback = null, $arguments = [])
    {
        $this->setType($type);
        $this->setSlug($slug);
        $this->setCallback($callback);
        $this->setArguments(array_merge($this->getDefaults(), $arguments));
    }

    /**
     * Dispach hook.
     *
     * @return bool
     */
    public function dispatch()
    {
        return $this->execute($this->getActionAlias('dispatch'), [
            $this->getSlug(),
            $this->getCallback(),
            $this->getArgument('piority'),
            $this->getArgument('numberOfArguments'),
        ]);
    }

    /**
     * Perform hook.
     *
     * @param mixed $parameters
     *
     * @return bool
     */
    public function perform($parameters)
    {
        return $this->execute($this->getActionAlias('perform'), [
            $this->getSlug(),
            $parameters,
        ]);
    }

    /**
     * Remove hook.
     *
     * @return bool
     */
    public function detach()
    {
        return $this->execute($this->getActionAlias('detach'), [
            $this->getSlug(),
            $this->getCallback(),
            $this->getArgument('piority'),
        ]);
    }

    /**
     * Execute hook.
     *
     * @param string $action
     * @param array $parameters
     *
     * @return bool
     */
    public function execute($action, $parameters)
    {
        return call_user_func_array("{$action}_{$this->getType()}", $parameters);
    }

    /**
     * Gets the hook type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the hook type.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Checks hook type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function isTypeOf($type)
    {
        return $this->type === $type;
    }

    /**
     * Get action name form map.
     *
     * @param string $name
     *
     * @return string
     */
    public function getActionAlias($name)
    {
        return $this->aliases[$this->getType()][$name];
    }

    /**
     * Gets the hook callback function.
     *
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Sets the hook callback function.
     *
     * @param mixed $callback
     *
     * @return self
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
