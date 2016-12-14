<?php

namespace Assely\Profile;

use Assely\Foundation\Factory;
use Assely\Contracts\Singularity\Model\ModelInterface;

class ProfileFactory extends Factory
{
    /**
     * Create profile.
     *
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface $model
     *
     * @return \Assely\Profile\ProfileSingularity
     */
    public function create(ModelInterface $model, $belongsTo = [])
    {
        $profile = $this->container->make(ProfileSingularity::class);

        $profile
            ->setModel($model)
            ->setBelongsTo($belongsTo);

        return $profile;
    }
}
