<?php

namespace Assely\Field\Support;

trait CanBeValidated
{
    /**
     * Field validator.
     *
     * @var \Assely\Field\FieldValidator
     */
    protected $validator;

    /**
     * Set field validate rules,
     *
     * @param  array $rules
     * @return self
     */
    public function validate($rules)
    {
        $this->getValidator()->setRules($rules)->mapRulesToAttributes();

        return $this;
    }

    /**
     * Gets the validator.
     *
     * @return \Assely\Field\FieldValidator
     */
    public function getValidator()
    {
        return $this->manager->getValidator();
    }
}
