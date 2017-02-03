<?php

class StoresValueTest extends TestCase
{
    /**
     * @test
     */
    public function value_can_be_set_and_get()
    {
        $stub = new StoresValueTraitStub;

        $this->assertInstanceOf('StoresValueTraitStub', $stub->setValue(['data' => 'value']));
        $this->assertEquals(['data' => 'value'], $stub->getValue());
    }
}

class StoresValueTraitStub
{
    use Assely\Support\Accessors\StoresValue;
}
