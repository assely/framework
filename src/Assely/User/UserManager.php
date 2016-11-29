<?php

namespace Assely\User;

use Assely\Singularity\Manager;

class UserManager extends Manager
{
    /**
     * Boot user manager.
     *
     * @param \Assely\User\User $user
     *
     * @return void
     */
    public function boot($user)
    {
        $this->user = $user;
    }

    /**
     * Register user list columns.
     *
     * @param void
     */
    public function columns()
    {
        $this->hook->filter(
            'manage_users_columns',
            [$this->user->getColumns(), 'setNames']
        )->dispatch();

        $this->hook->action(
            'manage_users_custom_column',
            function ($value, $name, $id) {
                ob_start();

                $column = $this->user->getColumn($name);

                if (isset($column)) {
                    $column->render($id);
                }

                return ob_get_clean();
            },
            ['numberOfArguments' => 3]
        )->dispatch();
    }
}
