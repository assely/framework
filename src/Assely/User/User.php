<?php

namespace Assely\User;

use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\UserModel;

abstract class User extends Repository
{
    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\UserModel
     */
    protected $user;

    /**
     * Repository factory.
     *
     * @var \Assely\User\UserFactory
     */
    protected $factory;

    /**
     * Hook service.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Construct repository.
     *
     * @param \Assely\Hook\HookFactory $hook
     */
    public function dispatch(
        UserModel $user,
        UserFactory $factory,
        HookFactory $hook
    ) {
        $this->user = $user;
        $this->factory = $factory;
        $this->hook = $hook;
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function register()
    {
        // Make comment model for the factory.
        $this->setModel($this->makeUserModel());

        // Build comment with model.
        $this->setSingularity($this->makeUserInstance());

        // Set columns to the users, after admin init.
        if ($this->methodExist('columns')) {
            $this->registerColumns();
        }
    }

    /**
     * Create user model.
     *
     * @return \Assely\Singularity\Model\UserModel
     */
    protected function makeUserModel()
    {
        return $this->user->make('users');
    }

    /**
     * Create user singularity instance.
     *
     * @return \Assely\User\User
     */
    protected function makeUserInstance()
    {
        return $this->factory->create($this->getModel());
    }

    /**
     * Register columns of the users list.
     *
     * @return void
     */
    protected function registerColumns()
    {
        $this->hook->action('admin_init', function () {
            $this->singularity->columns($this->columns());
        })->dispatch();
    }
}
