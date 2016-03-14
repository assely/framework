<?php

namespace Assely\Adapter;

use Assely\Adapters\Traits\FormatsCreationDate;
use Assely\Adapter\Traits\PerpetuatesModel;

class UserAdapter extends Adapter
{
    use PerpetuatesModel, FormatsCreationDate;

    /**
     * List of adaptee fields this adapter touches.
     *
     * @var array
     */
    public $touches = [
        'activation_key' => 'user_activation_key',
        'capabilities' => 'caps',
        'capability_key' => 'cap_key',
        'created_at' => 'user_registered',
        'email' => 'user_email',
        'id' => 'ID',
        'login' => 'user_login',
        'name' => 'display_name',
        'password' => 'user_pass',
        'premissions' => 'allcaps',
        'roles' => 'roles',
        'status' => 'user_status',
        'username' => 'user_nicename',
        'website' => 'user_url',
    ];

    /**
     * Connect user adapter.
     *
     * @return void
     */
    public function connect()
    {
        //
    }

    /**
     * User capability check.
     *
     * @param  string $capability
     *
     * @return boolean
     */
    public function can($capability)
    {
        return $this->adaptee->has_cap($capability);
    }

    /**
     * User meta data.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function meta($key = null)
    {
        if (isset($key)) {
            return $this->model->findMeta($this->id, $key);
        }

        return $this->model->getMeta($this->id);
    }

    /**
     * Check if user has role.
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * Handle json serialize.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'activation_key' => $this->activation_key,
            'capabilities' => $this->capabilities,
            'capability_key' => $this->capability_key,
            'created_at' => $this->created_at,
            'email' => $this->email,
            'id' => $this->id,
            'login' => $this->login,
            'meta' => $this->rejectHiddenMeta($this->meta),
            'name' => $this->name,
            'password' => $this->password,
            'premissions' => $this->premissions,
            'roles' => $this->roles,
            'status' => $this->status,
            'username' => $this->username,
            'website' => $this->website,
        ];
    }
}
