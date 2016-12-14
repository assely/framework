<?php

namespace Assely\User;

use Assely\Foundation\Factory;
use Assely\Contracts\Singularity\Model\ModelInterface;

class UserFactory extends Factory
{
    /**
     * Create user.
     *
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface $model
     *
     * @return \Assely\User\UserSingularity
     */
    public function create(ModelInterface $model)
    {
        $user = $this->container->make(UserSingularity::class);

        $user
            ->setModel($model)
            ->boot();

        return $user;
    }
}
