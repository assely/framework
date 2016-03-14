<?php

namespace Assely\Field\Support;

use Assely\Field\FieldException;
use Assely\Support\Descend;

trait CanBeSanitized
{
    /**
     * Sanitize field value.
     *
     * @param string|callable $callback
     * @return self
     *
     * @throws \Assely\Field\FieldException
     */
    public function sanitize($callback = 'sanitize_text_field')
    {
        if ($this->isValidCallback($callback)) {
            return $this->setArguments(['sanitize' => $callback]);
        }

        throw new FieldException('Your sanitize function is invalid.');
    }

    /**
     * Check if callback is strong or function.
     *
     * @param mxied $callback
     * @return boolean
     */
    public function isValidCallback($callback)
    {
        return is_string($callback) || is_callable($callback);
    }

    /**
     * Run sanitize callback on field value.
     *
     * @param string $value
     * @return string
     */
    public function sanitizeWithCallback($value)
    {
        if ($this->needsSanitization()) {
            return call_user_func(
                $this->getArgument('sanitize'),
                Descend::whileEmpty($value)
            );
        }

        return $value;
    }

    /**
     * Are we have sanitize argument.
     *
     * @return boolean
     */
    public function needsSanitization()
    {
        return  ! empty($this->getArgument('sanitize'));
    }
}
