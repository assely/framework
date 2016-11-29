<?php

namespace Assely\Thumbnail;

use Assely\Foundation\Depot;

class ThumbnailFactory extends Depot
{
    /**
     * Create thumbnail.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Thumbnail\Thumbnail
     */
    public function create($slug, array $arguments = [])
    {
        return $this->make($slug, $arguments);
    }

    /**
     * Get thumbnail.
     *
     * @param string $slug
     *
     * @throws ThumbnailException
     *
     * @return \Assely\Thumbnail\Thumbnail
     */
    public function get($slug)
    {
        // Check if we already have thumbnail with this slug.
        // in the depot. If so, pull it and return.
        if ($thumbnail = $this->reach($slug)) {
            return $thumbnail;
        }

        throw new ThumbnailException("Thumbnail [{$slug}] not found.");
    }

    /**
     * Make thumbnail instance.
     *
     * @param  string $slug
     * @param  array  $arguments
     *
     * @return \Assely\Thumbnail\Thumbnail
     */
    private function make($slug, array $arguments = [])
    {
        $thumbnail = $this->container->make(Thumbnail::class);

        $thumbnail
            ->setSlug($slug)
            ->setArguments($arguments)
            ->dispatch();

        return $this->hang($thumbnail);
    }
}
