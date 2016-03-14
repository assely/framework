<?php

class HasArgumentsTest extends TestCase
{
    /**
     * @test
     */
    public function can_access_class_defaults_arguments()
    {
        $stub = $this->getTraitStub();

        $this->assertEquals(['default' => 'argument'], $stub->getDefaults());
    }

    /**
     * @test
     */
    public function can_get_all_arguments()
    {
        $stub = $this->getTraitStub();

        $this->assertEquals(['default' => 'argument'], $stub->getArguments());
    }

    /**
     * @test
     */
    public function merges_current_arguments_with_new_ones_on_set()
    {
        $stub = $this->getTraitStub();

        $stub->setArguments(['new' => 'argument']);

        $this->assertEquals([
            'default' => 'argument',
            'new' => 'argument',
        ], $stub->getArguments());
    }

    /**
     * @test
     */
    public function can_get_specifed_single_argument_value()
    {
        $stub = $this->getTraitStub();

        $this->assertEquals('argument', $stub->getArgument('default'));
    }

    /**
     * @test
     */
    public function can_set_specifed_single_argument_value()
    {
        $stub = $this->getTraitStub();

        $stub->setArgument('default', 'updated');

        $this->assertEquals(['default' => 'updated'], $stub->getArguments());
    }

    public function getTraitStub()
    {
        return new HasArgumentsTraitStub;
    }
}

class HasArgumentsTraitStub
{
    use Assely\Support\Accessors\HasArguments;

    /**
     * @var array
     */
    private $defaults = [
        'default' => 'argument',
    ];

    public function __construct()
    {
        $this->setArguments($this->defaults);
    }
}
