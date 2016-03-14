<?php

namespace Assely\User;

use Assely\Contracts\Singularity\Model\ModelInterface;
use Assely\Foundation\Factory;

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
