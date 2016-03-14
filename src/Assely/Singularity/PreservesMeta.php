<?php

namespace Assely\Singularity;

use Illuminate\Support\Collection;

trait PreservesMeta
{
    /**
     * Get user meta data collection.
     *
     * @param  integer $id Term id
     *
     * @return array
     */
    public function getMeta($id)
    {
        $data = $this->resolveMeta('read', [$id, '', true]);

        return new Collection($data);
    }

    /**
     * Find user meta data
     *
     * @param  integer $id Term id
     *
     * @return array
     */
    public function findMeta($id, $key)
    {
        $meta = $this->getMeta($id);

        if (is_string($result = $meta->get($key))) {
            return $result;
        }

        return new Collection($result);
    }

    /**
     * Get all user meta data.
     *
     * @param  integer $id Term id
     *
     * @return array
     */
    public function getAllMeta($id)
    {
        return $this->getMeta($id)->all();
    }

    /**
     * Create user meta data.
     *
     * @param  integer $id
     * @param  string $key
     * @param  mixed $value
     *
     * @return boolean|integer
     */
    public function createMeta($id, array $arguments)
    {
        $parameters = $this->resolveMetaArguments($arguments);

        return $this->resolveMeta('make', [
            $id,
            $parameters['key'],
            $parameters['value'],
            $parameters['unique'],
        ]);
    }

    /**
     * Update user meta.
     *
     * @param  integer $id
     * @param  string $key
     * @param  mixed $value
     *
     * @return boolean|integer
     */
    public function updateMeta($id, array $arguments)
    {
        $parameters = $this->resolveMetaArguments($arguments);

        return $this->resolveMeta('save', [
            $id,
            $parameters['key'],
            $parameters['value'],
        ]);
    }

    /**
     * Delete model object meta data.
     *
     * @param integer $id
     * @param string $key
     *
     * @return boolean
     */
    public function deleteMeta($id, $key = '')
    {
        return $this->resolveMeta('remove', [$id, $key]);
    }
}
