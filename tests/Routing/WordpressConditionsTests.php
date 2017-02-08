<?php

use Assely\Routing\WordpressConditions;

class WordpressConditionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_on_accessing_nonregistered_condition()
    {
        $conditions = $this->getConditions();

        $this->expectException('Assely\Routing\RoutingException');

        $conditions->nonregistered;
    }

    /**
     * @test
     */
    public function it_should_return_condition_function_name_on_accessing_valid_condition()
    {
        $conditions = $this->getConditions();

        $this->assertEquals($conditions->page, 'is_page');
    }

    /**
     * @test
     */
    public function test_adding_new_conditions()
    {
        $conditions = $this->getConditions();

        $conditions->add(['is_shop' => 'shop']);

        $this->assertArrayHasKey('is_shop', $conditions->all());
        $this->assertEquals($conditions->get('is_shop'), 'shop');
    }

    /**
     * @test
     */
    public function test_conditions_evaluation()
    {
        $conditions = $this->getConditions();

        $this->assertTrue($conditions->is('page'));

        $this->assertTrue($conditions->is('page', 10));
    }

    public function getConditions()
    {
        return new WordpressConditions;
    }
}

function is_page($argument = null)
{
    return true;
}
