<?php

use Assely\Rewrite\Rule;
use Brain\Monkey\Functions;

class RuleTest extends TestCase
{
    /**
     * @test
     */
    public function test_default_regrex()
    {
        $this->assertEquals(Rule::DEFAULT_REGREX, '([^/]+)');
    }

    /**
     * @test
     */
    public function test_rule_adding()
    {
        $rule = $this->getRule();

        Functions::expect('add_rewrite_rule')
            ->once()
            ->with(Rule::DEFAULT_REGREX.'/?$', 'index.php?regrex=$matches[1]');

        $rule
            ->resolve('{regrex}')
            ->add();
    }

    /**
     * @test
     */
    public function test_parameters_extracting_from_pattern_and_conditions()
    {
        $rule = $this->getRule();

        $rule
            ->setRegrex('{one}/{two}')
            ->extractParameters([
                'one' => 'regrex_one',
                'two' => 'regrex_two',
            ]);

        $this->assertEquals($rule->getParameters(), [
            'one' => 'regrex_one',
            'two' => 'regrex_two',
        ]);
    }

    /**
     * @test
     */
    public function test_parameters_extracting_from_pattern_and_incomplete_conditions()
    {
        $rule = $this->getRule();

        $rule
            ->setRegrex('{one}/{two}')
            ->extractParameters([
                'one' => 'regrex_one',
            ]);

        $this->assertEquals($rule->getParameters(), [
            'one' => 'regrex_one',
            'two' => Rule::DEFAULT_REGREX,
        ]);
    }

    /**
     * @test
     */
    public function test_mock_replacing_in_regrex_string()
    {
        $rule = $this->getRule();

        $rule
            ->setRegrex('{one}/{two}')
            ->extractParameters([
                'one' => 'regrex_one',
            ])
            ->replaceMocksWithConditions();

        $this->assertEquals($rule->getRegrex(), 'regrex_one/'.Rule::DEFAULT_REGREX);
    }

    /**
     * @test
     */
    public function test_guid_string_generation_with_multiple_parameters()
    {
        $rule = $this->getRule();

        $rule
            ->setRegrex('{one}/{two}')
            ->extractParameters([])
            ->generateGuid();

        $this->assertEquals($rule->getGuid(), 'index.php?one=$matches[1]&two=$matches[2]');
    }

    /**
     * @test
     */
    public function test_guid_string_generation_with_no_parameters()
    {
        $rule = $this->getRule();

        $rule
            ->setRegrex('rule/path')
            ->extractParameters([])
            ->generateGuid();

        $this->assertEquals($rule->getGuid(), 'index.php');
    }

    public function getRule()
    {
        return new Rule;
    }
}
