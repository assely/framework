<?php

namespace Assely\Rewrite;

class Rewrite
{
    /**
     * Rewrite pattern.
     *
     * @var string
     */
    protected $pattern;

    /**
     * Rewrite conditions.
     *
     * @var array
     */
    protected $conditions = [];

    /**
     * Rewrite rule instance.
     *
     * @var \Assely\Rewrite\Rule
     */
    protected $rule;

    /**
     * Rewrite tag instance.
     *
     * @var \Assely\Rewrite\Tag
     */
    protected $tag;

    /**
     * Rewrite manager instance.
     *
     * @var \Assely\Rewrite\RewriteManager
     */
    protected $manager;

    /**
     * @param \Assely\Rewrite\Rule $rule
     * @param \Assely\Rewrite\Tag $tag
     * @param \Assely\Rewrite\RewriteManager $manager
     */
    public function __construct(
        Rule $rule,
        Tag $tag,
        RewriteManager $manager
    ) {
        $this->rule = $rule;
        $this->tag = $tag;
        $this->manager = $manager;

        $this->manager->boot($this);
    }

    /**
     * Registers rewrite rule and tags.
     *
     * @return void
     */
    public function register()
    {
        $this->rule->resolve(
            $this->pattern,
            $this->conditions
        )->add();

        if ( ! empty($parameters = $this->rule->getParameters())) {
            $this->tag->add($parameters);
        }
    }

    /**
     * Sets formats of rewrite tags.
     *
     * @param  array  $conditions
     *
     * @return self
     */
    public function where(array $conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Gets the Rewrite pattern.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Sets the Rewrite pattern.
     *
     * @param string $pattern the pattern
     *
     * @return self
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Gets the Rewrite rule instance.
     *
     * @return \Assely\Rewrite\Rule
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * Sets the Rewrite rule instance.
     *
     * @param \Assely\Rewrite\Rule $rule
     *
     * @return self
     */
    public function setRule(Rule $rule)
    {
        $this->rule = $rule;

        return $this;
    }

    /**
     * Gets the Rewrite tag instance.
     *
     * @return \Assely\Rewrite\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Sets the Rewrite tag instance.
     *
     * @param \Assely\Rewrite\Tag $tag
     *
     * @return self
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Gets the Rewrite slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->pattern;
    }
}
