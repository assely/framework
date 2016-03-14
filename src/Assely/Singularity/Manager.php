<?php

namespace Assely\Singularity;

use Assely\Asset\AssetFactory;
use Assely\Hook\HookFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

abstract class Manager
{
    /**
     * Hook factory.
     *
     * @var \Assely\Hook\HookFactory
     */
    public $hook;

    /**
     * Asset factory.
     *
     * @var \Assely\Asset\AssetFactory
     */
    public $asset;

    /**
     * View factory.
     *
     * @var \Assely\Contracts\View\ViewEngineInterface
     */
    public $view;

    /**
     * Construct manager.
     *
     * @param \Assely\Hook\HookFactory $hook
     */
    public function __construct(
        HookFactory $hook,
        AssetFactory $asset,
        ViewFactory $view
    ) {
        $this->hook = $hook;
        $this->view = $view;
        $this->asset = $asset;
    }

    /**
     * Boot manager service.
     *
     * @param  mixed $assistant
     */
    abstract public function boot($assistant);
}
