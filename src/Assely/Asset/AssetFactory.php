<?php

namespace Assely\Asset;

use Assely\Foundation\Depot;

class AssetFactory extends Depot
{
    /**
     * Add asset.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Asset\Asset
     */
    public function add($slug, $arguments = [])
    {
        return $this->make($slug, $arguments)->dispatchTo('add');
    }

    /**
     * Queue asset.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Asset\Asset
     */
    public function queue($slug, $arguments = [])
    {
        return $this->make($slug, $arguments);
    }

    /**
     * Load asset.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Asset\Asset
     */
    public function load($slug, $arguments = [])
    {
        return $this->make($slug, $arguments)->dispatchTo('enqueue');
    }

    /**
     * Remove asset.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Asset\Asset
     */
    public function remove($slug, $arguments = [])
    {
        return $this->make($slug, $arguments)->dispatchTo('deregister');
    }

    /**
     * Get asset.
     *
     * @param string $slug
     *
     * @throws AssetException
     *
     * @return \Assely\Asset\Asset
     *
     */
    public function get($slug)
    {
        // Check if we already have asset with this slug.
        // in the depot. If so, pull it and return.
        if ($asset = $this->reach($slug)) {
            return $asset;
        }

        throw new AssetException("Asset [{$slug}] not found.");
    }

    /**
     * Make asset.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Asset\Asset
     */
    private function make($slug, $arguments = [])
    {
        $asset = $this->container->make(Asset::class);

        $asset->setSlug($slug)->setArguments($arguments);

        return $this->hang($asset);
    }
}
