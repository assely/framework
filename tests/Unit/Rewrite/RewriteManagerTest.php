<?php

use Assely\Rewrite\RewriteManager;

class RewriteManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_hook_init_action_to_register_method_on_boot()
    {
        $hook = $this->getHookFactory();
        $asset = $this->getAssetFactory();
        $view = $this->getViewFactory();
        $manager = $this->getManager($hook, $asset, $view);
        $rewrite = $this->getRewrite();

        $hook->shouldReceive('action')->once()->with('init', [$rewrite, 'register'])->andReturn($hook);
        $hook->shouldReceive('dispatch')->once();

        $manager->boot($rewrite);
    }

    public function getRewrite()
    {
        return Mockery::mock('Assely\Rewrite\Rewrite');
    }

    public function getHookFactory()
    {
        return Mockery::mock('Assely\Hook\HookFactory');
    }

    public function getAssetFactory()
    {
        return Mockery::mock('Assely\Asset\AssetFactory');
    }

    public function getViewFactory()
    {
        return Mockery::mock('Illuminate\Contracts\View\Factory');
    }

    public function getManager($hook, $asset, $view)
    {
        return new RewriteManager($hook, $asset, $view);
    }
}
