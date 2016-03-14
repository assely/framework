<?php

namespace Assely\Field\Support;

trait MayBeConditional
{
    /**
     * Set condition fields.
     *
     * @param string $condition
     * @param array $fields
     *
     * @return self
     */
    public function when($condition, $fields)
    {
        $this->getChildren()->mergeSchema([
            $this->normalizeCondition($condition) => $fields,
        ]);

        return $this;
    }

    /**
     * Normalize condition for array key.
     *
     * @param mixed $condition
     *
     * @return string
     */
    public function normalizeCondition($condition)
    {
        if ($condition === true || $condition === 1) {
            return 'true';
        }

        if ($condition === false || $condition === 0) {
            return 'false';
        }

        return Helper::slugify($condition);
    }
}
