<?php

use Assely\Adapter\Adapter;
use Assely\Config\ApplicationConfig;

class AdapterTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_exception_on_accessing_non_existion_property()
    {
        $adapter = $this->getAdapter();

        $this->expectException('Exception');

        $adapter->nonExistingProperty;
    }

    /**
     * @test
     */
    public function test_property_getters()
    {
        $adapter = $this->getAdapter();

        $this->assertEquals('property', $adapter->property);
        $this->assertEquals('propertyByMethod', $adapter->propertyByMethod);
    }

    /**
     * @test
     */
    public function test_property_setters()
    {
        $adapter = $this->getAdapter();

        $adapter->property = 'updated-property';

        $this->assertEquals('updated-property', $adapter->property);
    }

    public function getAdapter()
    {
        return new AdapterStub(new ApplicationConfig);
    }
}

class AdapterStub extends Adapter
{
    public $property = 'property';

    public function propertyByMethod()
    {
        return 'propertyByMethod';
    }

    public function connect()
    {
        //
    }

    public function jsonSerialize()
    {
        //
    }
}
