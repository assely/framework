<?php

namespace Assely\Field;

use JsonSerializable;

class FieldValidator implements JsonSerializable
{
    /**
     * Validator rules to validate.
     *
     * @var array
     */
    private $rules = [];

    /**
     * Validator attributes for field created by rules.
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Construct validator.
     *
     * @param array $rules
     */
    public function __construct(array $rules = [])
    {
        $this->setRules($rules);
    }

    /**
     * Map rules for field attributes.
     *
     * @return void
     */
    public function mapRulesToAttributes()
    {
        foreach ($this->rules as $rule) {
            $params = explode(':', $rule);

            $this->attributes[reset($params)] = end($params);
        }
    }

    /**
     * Return rules and attributes
     * on json serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'rules' => $this->getRules(),
            'attributes' => $this->getAttributes(),
        ];
    }

    /**
     * Sets value of the rules.
     *
     * @param array $rules
     *
     * @return self
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        $this->mapRulesToAttributes();

        return $this;
    }

    /**
     * Get value of the rules.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Gets value of the attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
