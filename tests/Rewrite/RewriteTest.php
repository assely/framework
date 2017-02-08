<?php

use Assely\Rewrite\Rule;
use Assely\Rewrite\Rewrite;

class RewriteTest extends TestCase
{
    /**
     * @test
     */
    public function test_rewrite_rule_registration_without_tags()
    {
        $rule = $this->getRule();
        $tag = $this->getTag();
        $rewrite = $this->getRewrite($rule, $tag);

        $rule->shouldReceive('resolve')->once()->with('custom/rewrite/rule', [])->andReturn($rule);
        $rule->shouldReceive('add')->once()->andReturn(null);
        $rule->shouldReceive('getParameters')->once()->andReturn([]);

        $rewrite
            ->setPattern('custom/rewrite/rule')
            ->register();
    }

    /**
     * @test
     */
    public function test_rewrite_rule_registration_with_tags()
    {
        $rule = $this->getRule();
        $tag = $this->getTag();
        $rewrite = $this->getRewrite($rule, $tag);

        $rule->shouldReceive('resolve')->once()->with('custom/{rewrite}/{rule}', [])->andReturn($rule);
        $rule->shouldReceive('add')->once()->andReturn(null);
        $rule->shouldReceive('getParameters')->once()->andReturn([
            'rewrite' => Rule::DEFAULT_REGREX,
            'rule' => Rule::DEFAULT_REGREX,
        ]);
        $tag->shouldReceive('add')->once()->with([
            'rewrite' => Rule::DEFAULT_REGREX,
            'rule' => Rule::DEFAULT_REGREX,
        ])->andReturn(null);

        $rewrite
            ->setPattern('custom/{rewrite}/{rule}')
            ->register();
    }

    /**
     * @test
     */
    public function test_conditions_setter_and_getter()
    {
        $rule = $this->getRule();
        $tag = $this->getTag();
        $rewrite = $this->getRewrite($rule, $tag);

        $rewrite->where(['parameter' => 'condition']);

        $this->assertEquals($rewrite->getConditions(), ['parameter' => 'condition']);
    }

    /**
     * @test
     */
    public function test_pattern_and_slug_setter_and_getter()
    {
        $rule = $this->getRule();
        $tag = $this->getTag();
        $rewrite = $this->getRewrite($rule, $tag);

        $rewrite->setPattern('custom/rewrite/rule');

        $this->assertEquals($rewrite->getPattern(), 'custom/rewrite/rule');
        $this->assertEquals($rewrite->getSlug(), 'custom/rewrite/rule');
    }

    /**
     * @test
     */
    public function test_rule_and_tag_getter()
    {
        $rule = $this->getRule();
        $tag = $this->getTag();
        $rewrite = $this->getRewrite($rule, $tag);

        $this->assertEquals($rewrite->getRule(), $rule);
        $this->assertEquals($rewrite->getTag(), $tag);
    }

    public function getRule()
    {
        return Mockery::mock('Assely\Rewrite\Rule');
    }

    public function getTag()
    {
        return Mockery::mock('Assely\Rewrite\Tag');
    }

    public function getManager()
    {
        return Mockery::mock('Assely\Rewrite\RewriteManager');
    }

    public function getRewrite($rule, $tag)
    {
        $manager = $this->getManager();

        $manager->shouldReceive('boot')->once()->andReturn(null);

        return new Rewrite($rule, $tag, $manager);
    }
}
