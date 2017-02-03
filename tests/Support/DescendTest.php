<?php

use Assely\Support\Descend;

class DescendTest extends TestCase
{
    /**
     * @test
     */
    public function should_fallback_to_empty_string_if_descend_not_provided()
    {
        $value = [];

        $this->assertEquals('', Descend::whileEmpty($value));
    }

    /**
     * @test
     */
    public function should_fallback_provided_default_value()
    {
        $arrayVar = [];
        $stringVar = '';
        $nullVar = null;

        $this->assertEquals('default', Descend::whileEmpty($arrayVar, 'default'));
        $this->assertEquals('default', Descend::whileEmpty($stringVar, 'default'));
        $this->assertEquals('default', Descend::whileEmpty($nullVar, 'default'));
    }

    /**
     * @test
     */
    public function should_return_value_if_it_is_not_empty()
    {
        $arrayVar = ['key' => 'value'];
        $stringVar = 'string';
        $objVar = new stdClass;

        $this->assertEquals($arrayVar, Descend::whileEmpty($arrayVar));
        $this->assertEquals($stringVar, Descend::whileEmpty($stringVar));
        $this->assertEquals($objVar, Descend::whileEmpty($objVar));
    }
}
