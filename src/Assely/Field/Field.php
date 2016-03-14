<?php

namespace Assely\Field;

use Assely\Contracts\Field\FieldInterface;
use Assely\Support\Accessors\HasArguments;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Assely\Support\Accessors\UsesFingerprint;
use Illuminate\Contracts\View\Factory as ViewFactory;
use JsonSerializable;

abstract class Field implements FieldInterface, JsonSerializable
{
    use HasSlug, HasArguments, HasTitles, UsesFingerprint;

    /**
     * Default field arguments.
     *
     * @var array
     */
    protected $defaults = [
        'title' => [],
        'description' => '',
        'default' => null,
        'items' => [],
        'column' => '1',
        'sanitize' => false,
    ];

    /**
     * Field manager.
     *
     * @var FieldManager
     */
    protected $manager;

    /**
     * Field paths.
     *
     * @var array
     */
    protected $paths;

    /**
     * Field value.
     *
     * @var string
     */
    protected $value;

    /**
     * Field type.
     *
     * @var string
     */
    protected $type;

    /**
     * Construct field.
     *
     * @param \Assely\Field\FieldManager $manager
     * @param \Assely\Field\FieldsCollection $children
     * @param array $paths
     * @param string $type
     * @param string $slug
     * @param array $arguments
     */
    public function __construct(
        FieldManager $manager,
        FieldsCollection $children
    ) {
        $this->manager = $manager;
        $this->children = $children;
    }

    /**
     * Bootstrap field instance.
     *
     * @return void
     */
    public function boot()
    {
        $this->setFingerprint(mt_rand());

        $this->setArguments($this->getMessages());
        $this->setSingular($this->getArgument('title'));
        $this->setPlural($this->getArgument('title'));
        // $this->setValue($this->getArgument('default'));

        $this->manager->boot($this);
    }

    /**
     * Dispach field values.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->manager->dispatchTemplate();

        if ($this->hasChildren()) {
            $this->getChildren()
                ->setParent($this)
                ->boostSchemaWithValues($this->getValue());

            if ($this->isTypeOf('repeatable') && empty($this->getValue())) {
                $this->dispatchSchema();
            }
        }
    }

    /**
     * Dispach field schema.
     *
     * @return void
     */
    public function dispatchSchema()
    {
        foreach ($this->children->getSchema() as $field) {
            if (is_array($field)) {
                foreach ($field as $key => $item) {
                    $item->setValue($item->getArgument('default'))->dispatch();
                }

                continue;
            }

            $field->setValue($field->getArgument('default'))->dispatch();
        }
    }

    /**
     * Render field template.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function template(ViewFactory $view)
    {
        throw new FieldException(ucfirst($this->type) . ' Field must have template.');
    }

    /**
     * By default field can't have conditional fields. This behavior
     * is overwrited by specifed field type implementation.
     *
     * @param  string $condition
     * @param  array $fields
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function on($condition, $fields)
    {
        throw new FieldException(ucfirst($this->type) . " Field can't have conditional fields.");
    }

    /**
     * By default field can't have children fields. This behavior
     * is overwrited by specifed field type implementation.
     *
     * @param  array $fields
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function children($fields)
    {
        throw new FieldException(ucfirst($this->type) . " Field can't have children fields.");
    }

    /**
     * By default field can't be validated. This behavior is
     * overwrited by specifed field type implementation.
     *
     * @param  array $rules
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function validate($rules)
    {
        throw new FieldException(ucfirst($this->type) . " Field can't be validated.");
    }

    /**
     * By default field can't be serialized. This behavior is
     * overwrited by specifed field type implementation.
     *
     * @param  string|callable $callback
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function sanitize($callback = '')
    {
        throw new FieldException(ucfirst($this->type) . " Field can't be sanitized.");
    }

    /**
     * By default field can't have column preview. This behavior
     * is overwrited by specifed field type implementation.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     * @param  mixed $value
     *
     * @throws \Assely\Field\FieldException
     *
     * @return void
     *
     */
    public function preview(ViewFactory $view, $value)
    {
        throw new FieldException(ucfirst($this->type) . " Field can't have column preview.");
    }

    /**
     * By default field can't have children, so we are returning false.
     * This behavior is overwrited by specifed field implementation.
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * By default field can not be sanitized, so we are returning false.
     * This behavior is overwrited by specifed field implementation.
     *
     * @return boolean
     */
    public function needsSanitization()
    {
        return false;
    }

    /**
     * Serialize Field to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $children = ($this->hasChildren()) ? $this->children : [];

        return [
            'slug' => $this->getSlug(),
            'type' => $this->getType(),
            'arguments' => $this->getArguments(),
            'singular' => $this->getSingular(),
            'plural' => $this->getPlural(),
            'value' => $this->getValue(),
            'fingerprint' => $this->getFingerprint(),
            'children' => $children,
            'validator' => $this->manager->getValidator(),
        ];
    }

    /**
     * Get default field messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return [
            'messages' => [
                'add' => 'Add entry',
                'edit' => 'Edit entry',
                'clear' => 'Remove all entries',
                'empty' => 'No entries. Maybe you should add one?',
                'search' => 'What entry do you looking for?',
            ],
        ];
    }

    /**
     * Check if field is specifed type.
     *
     * @param  string  $type
     *
     * @return boolean
     */
    public function isTypeOf($type)
    {
        return $this->getType() === $type;
    }

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value.
     *
     * @param mixed $value the value
     *
     * @return self
     */
    public function setValue($value)
    {
        if (null === $value) {
            $value = $this->getArgument('default');
        }

        $this->value = ($this->needsSanitization())
            ? $this->sanitizeWithCallback($value)
            : $value;

        return $this;
    }

    /**
     * Gets Field Manager
     *
     * @return \Assely\Field\FieldManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Defines procedure of field cloning.
     *
     * @return \Assely\Contracts\Field\FieldInterface
     */
    public function __clone()
    {
        $clone = $this;

        if ($this->hasChildren()) {
            $clone->setChildren(clone $this->getChildren());
        }

        return $clone;
    }
}
