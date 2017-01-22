<?php

use Brain\Monkey\Functions;
use Assely\Support\Supports;

class SupportsTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_register_single_support_option_given_as_string()
    {
        $supports = $this->getSupports();

        Functions::expect('add_theme_support')
            ->once()
            ->with('option');

        $supports->add('option');
    }

    /**
     * @test
     */
    public function it_should_register_multiple_support_options_given_as_array()
    {
        $supports = $this->getSupports();

        Functions::expect('add_theme_support')
            ->once()
            ->with('option1');

        Functions::expect('add_theme_support')
            ->once()
            ->with('option2', 'value');

        $supports->add([
            'option1' => true,
            'option2' => 'value',
        ]);
    }

    /**
     * @test
     */
    public function test_support_option_value_getter()
    {
        $supports = $this->getSupports();

        Functions::expect('get_theme_support')
            ->once()
            ->with('option');

        $supports->get('option');
    }

    public function getSupports()
    {
        return new Supports;
    }
}
