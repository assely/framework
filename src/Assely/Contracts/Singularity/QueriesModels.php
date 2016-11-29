<?php

namespace Assely\Contracts\Singularity;

use Assely\Contracts\Adapter\AdapterInterface;

interface QueriesModels
{
    /**
     * Find model object.
     *
     * @param  int $id
     */
    public function find($id);

    /**
     * Find model object or trow if unsuccessful.
     *
     * @param  int $id
     */
    public function findOrFail($id);

    /**
     * Fetch all model objects.
     */
    public function all();

    /**
     * Create model object.
     *
     * @param array $properties
     */
    public function create(array $properties);

    /**
     * Create model object or trow if unsuccessful.
     *
     * @param array $properties
     */
    public function createOrFail(array $properties);

    /**
     * Update model object.
     *
     * @param  int $id
     * @param  array $arguments
     */
    public function update(AdapterInterface $adapter);

    /**
     * Update model object or trow if unsuccessful.
     *
     * @param  int $id
     * @param  array $arguments
     */
    public function updateOrFail(AdapterInterface $adapter);

    /**
     * Delete model object.
     *
     * @param  int $id
     */
    public function delete($id);
}
