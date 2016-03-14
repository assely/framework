<?php

namespace Assely\Contracts\Singularity;

interface PreservesMetaInterface
{
    /**
     * Read model object meta data from Singularity.
     *
     * @param  integer $id
     * @param  string $key
     */
    public function readMeta(array $parameters);

    /**
     * Save model object meta data to Singularity.
     *
     * @param  integer $id
     * @param  string $key
     */
    public function saveMeta(array $parameters);

    /**
     * Delete model object meta data from Singularity.
     *
     * @param  integer $id
     * @param  string $key
     */
    public function removeMeta(array $parameters);

    /**
     * Get model object meta data.
     *
     * @param  integer $id
     * @param  string $key
     */
    public function findMeta($id, $key);

    /**
     * Get all model object meta data.
     *
     * @param  integer $id
     */
    public function getMeta($id);

    /**
     * Get model object meta data collection.
     *
     * @param  integer $id
     */
    public function getAllMeta($id);

    /**
     * Create model object meta data.
     *
     * @param  integer $id
     * @param  array $arguments
     */
    public function createMeta($id, array $arguments);

    /**
     * Update model object meta data.
     *
     * @param  integer $id
     * @param  array $arguments
     */
    public function updateMeta($id, array $arguments);

    /**
     * Delete model object meta data.
     *
     * @param  integer $id
     */
    public function deleteMeta($id, $key);
}
