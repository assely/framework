<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\User;
use Assely\Singularity\Model;
use Assely\Singularity\PreservesMeta;
use Assely\Singularity\QueryException;
use Assely\Contracts\Singularity\WPQueryable;
use Assely\Contracts\Singularity\PreservesMetaInterface;

class UserModel extends Model implements PreservesMetaInterface, WPQueryable
{
    use PreservesMeta;

    /**
     * Model defaults.
     *
     * @var array
     */
    protected $defaults = [
        'preserve' => 'multiple',
    ];

    /**
     * Query.
     *
     * @param  array $arguments
     *
     * @return mixed
     */
    public function query(array $arguments = [])
    {
        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(User::class)
            ->plugIn(get_users($arguments))
            ->getConnected();
    }

    /**
     * Find user by keys.
     *
     * @param  array  $key
     * @param  string $value
     *
     * @return \WP_User|false
     */
    public function findBy($key, $value)
    {
        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(User::class)
            ->plugIn(get_user_by($key, $value))
            ->getConnected()
            ->first();
    }

    /**
     * Find user by id.
     *
     * @param int $id
     *
     * @return \WP_User|false
     */
    public function find($id)
    {
        return $this->findBy('id', $id);
    }

    /**
     * Find user by id or trow if unsuccessful.
     *
     * @param int $id
     *
     * @throws \Assely\Singularity\QueryException
     *
     * @return \WP_User|false
     */
    public function findOrFail($id)
    {
        $user = $this->find($id);

        if (! isset($user->id)) {
            throw new QueryException("User [{$id}] not found.");
        }

        return $user;
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function all()
    {
        return $this->query()->all();
    }

    /**
     * Get all users with role.
     *
     * @param string $role
     *
     * @return \Illuminate\Support\Collection
     */
    public function withRole($role)
    {
        return $this->query([
            'role__in' => $role,
        ]);
    }

    /**
     * Create user.
     *
     * @param array $arguments
     *
     * @return int|\WP_Error
     */
    public function create(array $arguments)
    {
        return wp_insert_user($this->prefixKeys($arguments, $this->prefix));
    }

    /**
     * Update user.
     *
     * @param \Assely\Adapter\User $user
     *
     * @return int|\WP_Error
     */
    public function update(User $user)
    {
        return wp_update_user($user->getPlug());
    }

    /**
     * Delete user.
     *
     * @param \Assely\Adapter\User $user
     * @param \Assely\Adapter\User $reassign
     *
     * @return bool
     */
    public function delete(User $user, User $successor = null)
    {
        require_once ABSPATH.'wp-admin/includes/user.php';

        if ($successor instanceof User) {
            $successor = $successor->id;
        }

        return wp_delete_user($user->id, $successor);
    }

    /**
     * Read user meta from Singularity.
     *
     * @param array $parameters
     *
     * @return array
     */
    public function readMeta(array $parameters)
    {
        return call_user_func_array('get_user_meta', $parameters);
    }

    /**
     * Save user meta to Singularity.
     *
     * @param array $parameters
     *
     * @return bool|int
     */
    public function makeMeta(array $parameters)
    {
        return call_user_func_array('add_user_meta', $parameters);
    }

    /**
     * Save user meta to Singularity.
     *
     * @param array $parameters
     *
     * @return bool|int
     */
    public function saveMeta(array $parameters)
    {
        return call_user_func_array('update_user_meta', $parameters);
    }

    /**
     * Delete user meta to Singularity.
     *
     * @param array $parameters
     *
     * @return bool|int
     */
    public function removeMeta(array $parameters)
    {
        return call_user_func_array('delete_user_meta', $parameters);
    }
}
