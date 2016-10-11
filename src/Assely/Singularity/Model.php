<?php

namespace Assely\Singularity;

use Assely\Adapter\AdapterPlugger;
use Assely\Contracts\Singularity\Model\ModelInterface;
use Assely\Nonce\NonceFactory;
use Assely\Support\Accessors\HasArguments;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Assely\Support\Accessors\UsesFingerprint;
use Illuminate\Support\Collection;

abstract class Model implements ModelInterface
{
    use HasSlug, HasArguments, HasTitles, UsesFingerprint;

    /**
     * Nonce factory instance.
     *
     * @var \Assely\Nonce\NonceFactory
     */
    protected $nonce;

    /**
     * Meta arguments metaDefaults.
     *
     * @var array
     */
    protected $metaDefaults = [
        'key' => '',
        'value' => null,
        'unique' => true,
    ];

    /**
     * Construct model.
     *
     * @param \Assely\Nonce\NonceFactory $nonce
     */
    public function __construct(NonceFactory $nonce)
    {
        $this->nonce = $nonce;

        $this->setArguments($this->getDefaults());
    }

    /**
     * Make model.
     *
     * @param string $slug
     * @param array $arguments
     *
     * @return self
     */
    public function make($slug, array $arguments = [])
    {
        $this->setSlug($slug);
        $this->setArguments($arguments);
        $this->setSingular($this->getArgument('title'));
        $this->setPlural($this->getArgument('title'));
        $this->setFingerprint();

        return $this;
    }

    /**
     * Save model.
     *
     * @param integer $id
     * @param mixed $values
     *
     * @return void
     */
    public function save($id, $values)
    {
        // We need to verify nonce value, before
        // we save model data to the Singularity.
        $this->nonce->verify($this->getSlug());

        // If model is preserved as single, update
        // single row with model slug as meta key.
        if ($this->isPreservedAs('single')) {
            return $this->updateMeta($id, [
                'key' => $this->getSlug(),
                'value' => $values,
            ]);
        }

        // If model is preserved as multiple, update
        // multiple rows where meta key is value key.
        if ($this->isPreservedAs('multiple')) {
            foreach ($values as $key => $value) {
                $this->updateMeta($id, [
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    /**
     * Resolve meta data.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function resolveMeta($action, $arguments)
    {
        // Model meta data is saved as single record. In this
        // case meta data key is always model slug, so to
        // be sure, we overwrite this argument.
        if ($this->isPreservedAs('single')) {
            $arguments[1] = $this->getSlug();

            return $this->{"{$action}Meta"}($arguments);
        }

        // Model meta data is saved as multiple records. Unserialize
        // Singularity array string, before returning the results.
        if ($this->isPreservedAs('multiple')) {
            return $this->unserializeCollection($this->{"{$action}Meta"}($arguments));
        }

        // Model meta don't have any preserve settings.
        // Just process action and nothing more.
        return $this->{"{$action}Meta"}($arguments);
    }

    /**
     * Resolve and verify meta process arguments.
     *
     * @param  array $arguments
     *
     * @return array
     */
    public function resolveMetaArguments($arguments)
    {
        if ($this->isPreservedAs('multiple') && ! isset($arguments['key'])) {
            throw new MetaArgumentsException("You need to provide key for processing Taxonomy [{$this->slug}] meta.");
        }

        return array_merge($this->metaDefaults, $arguments);
    }

    /**
     * Unserialize data collection.
     *
     * @param  array $collection
     *
     * @return array
     */
    public function unserializeCollection($collection)
    {
        if (is_array($collection)) {
            return array_map(function ($value) {
                return maybe_unserialize(reset($value));
            }, $collection);
        }

        return maybe_unserialize($collection);
    }

    /**
     * How this repository have to preserve values.
     *
     * @throws \Assely\Singularity\QueryException
     *
     * @return string
     *
     */
    public function preserveAs()
    {
        $arguments = $this->getArguments();

        if (! isset($arguments['preserve'])) {
            throw new QueryException('You need to specify how this repository should be preserved [single, multiple or default].');
        }

        if (! in_array($arguments['preserve'], ['single', 'multiple', 'default'])) {
            throw new QueryException('Repository can be preserved only as single, multiple or default.');
        }

        return $arguments['preserve'];
    }

    /**
     * Check model preserve method.
     *
     * @param  string $type
     *
     * @return boolean
     */
    public function isPreservedAs($type)
    {
        return $this->preserveAs() === $type;
    }

    /**
     * Gets adapters plugger.
     *
     * @return \Assely\Adapter\AdapterPlugger
     */
    public function getAdapterPlugger()
    {
        return new AdapterPlugger(new Collection);
    }

    /**
     * Plug adapters to adaptee.
     *
     * @param  \Assely\Contracts\Adapter\AdapterInterface $adapter
     * @param  array $adaptees
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface|null $model
     *
     * @return \Illuminate\Support\Collection
     */
    public function plugAdapter(
        $adapter,
        array $adaptees,
        ModelInterface $model = null
    ) {
        return $this->getAdapterPlugger()
            ->setModel($model ?: $this)
            ->setAdapter($adapter)
            ->plugIn($adaptees)
            ->getConnected();
    }

    /**
     * Explode pipline arguments to array.
     *
     * @param  string $arguments
     * @param  string $prefix
     *
     * @return array
     */
    public function explodePipelinedArgument($arguments, $prefix = '')
    {
        return $this->prefixValues(explode('|', $arguments), $prefix);
    }

    /**
     * Prefix and normalize methods arguments.
     *
     * @param  array  $arguments
     * @param  string $prefix
     *
     * @return array
     */
    public function prefixValues(array $arguments, $prefix)
    {
        $skip = ['id', 'ID'];

        return array_map(function ($key) use ($prefix, $skip) {
            if (in_array($key, $skip)) {
                return $key;
            }

            return "{$prefix}{$key}";
        }, $arguments);
    }

    /**
     * Prefix arguments keys.
     *
     * @param  array  $arguments
     * @param  string $prefix
     *
     * @return array
     */
    public function prefixKeys(array $arguments, $prefix)
    {
        $normalized = [];
        $skip = ['id', 'ID'];

        foreach ($arguments as $key => $value) {
            if (in_array($key, $skip)) {
                $normalized[$key] = $value;

                continue;
            }

            $normalized["{$prefix}{$key}"] = $value;
        }

        return $normalized;
    }

    /**
     * Handle dynamic static method calls.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = new static;

        return call_user_func_array([$instance, $method], $parameters);
    }
}
