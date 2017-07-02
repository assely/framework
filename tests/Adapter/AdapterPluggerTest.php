<?php

use Assely\Adapter\Post;
use Assely\Adapter\AdapterPlugger;
use Illuminate\Support\Collection;
use Assely\Config\ApplicationConfig;

class AdapterPluggerTest extends TestCase
{
    /**
     * @test
     */
    public function test_plugging_in_adapter_to_the_adaptees()
    {
        $model = $this->getModel();
        $plugger = $this->getPlugger();

        $result = $plugger
            ->setModel($model)
            ->setAdapter(Post::class)
            ->plugIn([new WP_Post, new WP_Post])
            ->getConnected();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertContainsOnlyInstancesOf(Post::class, $result);
    }

    protected function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\PosttypeModel');
    }

    protected function getPlugger()
    {
        return new AdapterPlugger(
            new Collection,
            new ApplicationConfig
        );
    }
}
