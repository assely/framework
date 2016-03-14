<?php

namespace Assely\Field;

use Illuminate\Support\Arr;
use JsonSerializable;

class FieldsCollection implements JsonSerializable
{
    /**
     * Fields collection
     *
     * @var \Assely\Field\Field[]
     */
    protected $fields = [];

    /**
     * Fields schema
     *
     * @var \Assely\Field\Field[]
     */
    protected $schema = [];

    /**
     * Fields values
     *
     * @var array
     */
    protected $values = [];

    /**
     * Propagation heap.
     *
     * @var array
     */
    protected $heap = [];

    /**
     * Parent field of firlds collection.
     *
     * @var \Assely\Field\Field
     */
    protected $parent;

    /**
     * Construnct Fields Collection
     *
     * @param array $fields
     * @param array $schema
     * @param array $values
     */
    public function __construct(FieldsFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Boost schema with values.
     *
     * @param  array $values
     *
     * @return void
     */
    public function boostSchemaWithValues($values)
    {
        $this->setValues($values)->propagateSchemaWithValues();
    }

    /**
     * Propagate fields schemas with values.
     *
     * @return void
     */
    public function propagateSchemaWithValues()
    {
        array_walk($this->schema, function ($field, $index) {
            if ($this->hasParent() and $this->getParent()->isTypeOf('repeatable')) {
                return $this->propagateAsRepeatableSchema($field, $index);
            }

            return $this->propagateAsNormalSchema($field);
        });

        return $this->mergeFields($this->heap);
    }

    /**
     * Propagate schema for repeatable field.
     *
     * @param \Assely\Field\Field $field
     *
     * @return self
     */
    public function propagateAsRepeatableSchema(Field $field, $index)
    {
        if ($field->isTypeOf('repeatable')) {
            throw new FieldsCollectionException("Repeatable fields [{$field->getSlug()}] cannot be nested.");
        }

        foreach ($this->getValues() as $key => $value) {
            $clone = clone $field;

            $clone->setValue($value[$clone->getSlug()])->dispatch();

            $this->heap[$key][$index] = $clone;
        }
    }

    /**
     * Propagate schema for normal field.
     *
     * @param \Assely\Field\Field $field
     *
     * @return self
     */
    public function propagateAsNormalSchema(Field $field)
    {
        $field->setValue($this->getValue($field->getSlug()))->dispatch();

        return $this->pushFields($field);
    }

    /**
     * Sanitize values.
     *
     * @param  mixed &$values
     *
     * @return mixed
     */
    public function getSanitizedValues(&$values = [])
    {
        array_walk($this->fields, function ($field, $index) use (&$values) {
            if (is_array($field)) {
                $this->sanitizeConditionalValues($values, $field, $index);
            } else {
                $this->sanitizeValues($values[$field->getSlug()], $field);
            }
        });

        return $values;
    }
    /**
     * Sanitize values in associative array.
     *
     * @param  mixed &$values
     * @param  array $fields
     */
    public function sanitizeConditionalValues(&$values, $fields, $index)
    {
        foreach ($fields as $key => $field) {
            if ($values === $index) {
                $this->sanitizeValues($values[$index][$field->getSlug()], $field);
            }
        }
    }
    /**
     * Sanitize field values with field.
     *
     * @param  mixed &$values
     * @param  Field $field
     */
    public function sanitizeValues(&$values, $field)
    {
        $values = $field->getValue();

        if ($field->hasChildren()) {
            $field->getChildren()->getSanitizedValues($values);
        }
    }

    /**
     * Get field from collection by slug.
     *
     * @param  string $slug Field slug
     *
     * @return Field
     */
    public function getWithPath($field)
    {
        return $this->finder
            ->setSchema($this->getSchema())
            ->find($field);
    }

    /**
     * On json serialization return
     * collection of fields and schema.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'fields' => $this->getFields(),
            'schema' => $this->getSchema(),
        ];
    }

    /**
     * Get fields collection.
     *
     * @return array
     */
    public function getAll()
    {
        return [$this->getFields()];
    }

    /**
     * Gets the value of fields.
     *
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Sets the value of fields.
     *
     * @param mixed $fields the children
     *
     * @return self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Push values to fields.
     *
     * @param  array $fields
     *
     * @return self
     */
    public function pushFields($fields)
    {
        $this->fields[] = $fields;

        return $this;
    }

    /**
     * Merge values of fields.
     *
     * @param  array $fields
     *
     * @return array
     */
    public function mergeFields($fields)
    {
        $this->fields = array_merge($this->fields, $fields);

        return $this;
    }

    /**
     * Gets the value of schema.
     *
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Sets the value of schema.
     *
     * @param mixed $schema the children
     *
     * @return self
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Push values to schema.
     *
     * @param  array $schema
     *
     * @return self
     */
    public function pushSchema($schema)
    {
        $this->schema[] = $schema;

        return $this;
    }

    /**
     * Merge values of schema.
     *
     * @param  array $schema
     *
     * @return array
     */
    public function mergeSchema($schema)
    {
        $this->schema = array_merge($this->schema, $schema);

        return $this;
    }

    /**
     * Gets the value of values.
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue($key)
    {
        return Arr::get($this->values, $key, []);
    }

    /**
     * Sets the value of values.
     *
     * @param mixed $values the values
     *
     * @return self
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Gets the value of parent.
     *
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets the value of parent.
     *
     * @param mixed $parent the parent
     *
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Has field collection a parent field?
     *
     * @return boolean
     */
    public function hasParent()
    {
        return isset($this->parent);
    }
}
