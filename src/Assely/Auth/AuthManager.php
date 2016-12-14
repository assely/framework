<?php

namespace Assely\Auth;

use WP_User;
use Assely\Adapter\User;
use Assely\Singularity\Model\UserModel;

class AuthManager
{
    /**
     * Currently logged in user.
     *
     * @var \Assely\Adapter\User
     */
    protected $user;

    /**
     * Construct auth manager.
     *
     * @param \Assely\Singularity\Model\UserModel $model
     * @param \Assely\Adapter\User $adapter
     */
    public function __construct(
        UserModel $model,
        User $adapter
    ) {
        $this->setLoggedinUser($model, $adapter);
    }

    /**
     * Get logged in user object.
     *
     * @return void
     */
    private function setLoggedinUser(
        UserModel $model,
        User $adapter
    ) {
        $wp_user = new WP_User($this->getCurrentUserId());

        $adapter
            ->setModel($model)
            ->setAdaptee($wp_user)
            ->connect();

        $this->setUser($adapter);
    }

    /**
     * Get currently loged in user id.
     *
     * @return int
     */
    public function getCurrentUserId()
    {
        return get_current_user_id();
    }

    /**
     * Determine if the user is already logged in.
     *
     * @return bool
     */
    public function check()
    {
        return is_user_logged_in();
    }

    /**
     * Attempt user login.
     *
     * @param array $credentials
     * @param bool $remember
     *
     * @return bool
     */
    public function attempt(array $credentials, $remember = false)
    {
        $credentials = array_merge($credentials, ['remember' => $remember]);

        $user = wp_signon($credentials);

        if (! is_wp_error($user)) {
            return true;
        }

        return false;
    }

    /**
     * Login with User istance.
     *
     * @param \Assely\Adapter\User $user
     * @param bool $remember
     * @param string $secure
     *
     * @return void
     */
    public function login(
        User $user,
        $remember = false,
        $secure = ''
    ) {
        wp_set_auth_cookie($user->id, $remember, $secure);

        wp_set_current_user($user->id);
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        return wp_logout();
    }

    /**
     * Gets the Currently logged in user.
     *
     * @return \Assely\Adapter\User
     */
    public function user()
    {
        if ($this->check()) {
            return $this->user;
        }
    }

    /**
     * Sets the Currently logged in user.
     *
     * @param \Assely\Adapter\User $user the user
     *
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }
}
