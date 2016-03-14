<?php

namespace Assely\Profile;

use Assely\Auth\AuthManager;
use Assely\Hook\HookFactory;
use Assely\Repository\Repository;
use Assely\Singularity\Model\ProfileModel;

abstract class Profile extends Repository
{
    /**
     * Repository model.
     *
     * @var \Assely\Singularity\Model\ProfileModel
     */
    protected $profile;

    /**
     * Repository factory.
     *
     * @var \Assely\Profile\ProfileFactory
     */
    protected $factory;

    /**
     * Hook service.
     *
     * @var \Assely\Auth\AuthManager
     */
    protected $auth;

    /**
     * Hook service.
     *
     * @var \Assely\Hook\HookFactory
     */
    protected $hook;

    /**
     * Dispatch repository.
     *
     * @param \Assely\Singularity\Model\ProfileModel $profile
     * @param \Assely\Profile\ProfileFactory $factory
     * @param \Assely\Auth\AuthManager $auth
     * @param \Assely\Hook\HookFactory $hook
     *
     * @return void
     */
    public function dispatch(
        ProfileModel $profile,
        ProfileFactory $factory,
        AuthManager $auth,
        HookFactory $hook
    ) {
        $this->profile = $profile;
        $this->factory = $factory;
        $this->auth = $auth;
        $this->hook = $hook;
    }

    /**
     * Register repository.
     *
     * @return void
     */
    protected function register()
    {
        // Make profile model for factory.
        $this->setModel($this->makeProfileModel());

        if ($this->meetsConditions()) {
            // If profile is depending on other singularities,
            // register this profile only for them.
            if ($this->haveHolders()) {
                return $this->registerProfileForHolders();
            }

            // Profile do not have any holders.
            // Register profile for everyone.
            return $this->registerProfile();
        }
    }

    /**
     * Create taxonomy model.
     *
     * @return \Assely\Singularity\Model\ProfileModel
     */
    protected function makeProfileModel()
    {
        return $this->profile->make($this->slug, $this->arguments());
    }

    /**
     * Create taxonomy singularity instance.
     *
     * @return \Assely\Profile\Profile
     */
    protected function makeProfileInstance()
    {
        return $this->factory->create($this->getModel(), $this->belongsTo());
    }

    /**
     * Check if we meet conditions for
     * the profile registration.
     *
     * @return boolean
     */
    protected function meetsConditions()
    {
        // If profile visibility is public, it can be display
        // for everyone, so users can edit own profiles.
        if (isset($this->visibility) && $this->visibility === 'public') {
            return true;
        }

        // Profile is hidden. Can be displayed only
        // for users that can edit user profiles.
        return $this->auth->user()->can('edit_user');
    }

    /**
     * Check if profile have holders.
     *
     * @return boolean
     */
    public function haveHolders()
    {
        return  ! empty($this->belongsTo());
    }

    /**
     * Register profile for each holder
     * if his conditions are meet.
     *
     * @return void
     */
    protected function registerProfileForHolders()
    {
        foreach ($this->resolveHolders() as $holder) {
            if ($holder->meetsConditions()) {
                $this->registerProfile();
            }
        }
    }

    /**
     * Create and register profile.
     *
     * @return void
     */
    protected function registerProfile()
    {
        // Build profile with model and singularity holders.
        $this->setSingularity($this->makeProfileInstance());

        // Set fields to the profile.
        if ($this->methodExist('fields')) {
            $this->registerFields();
        }
    }

    /**
     * Register singularity fields.
     *
     * @return \Assely\Profile\Profile
     */
    protected function registerFields()
    {
        return $this->getSingularity()->fields($this->fields());
    }
}
