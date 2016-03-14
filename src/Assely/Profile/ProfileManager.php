<?php

namespace Assely\Profile;

use Assely\Singularity\Manager;

class ProfileManager extends Manager
{
    /**
     * Boot profile manager.
     *
     * @param  \Assely\Profile\Profile $profile
     */
    public function boot($profile)
    {
        $this->profile = $profile;

        $this->hooks();
    }

    /**
     * Register profile hooks.
     *
     * @return void
     */
    public function hooks()
    {
        $this->hook->action(
            'show_user_profile',
            [$this->profile, 'fill']
        )->dispatch();

        $this->hook->action(
            'edit_user_profile',
            [$this->profile, 'fill']
        )->dispatch();

        $this->hook->action(
            'personal_options_update',
            [$this->profile, 'save']
        )->dispatch();

        $this->hook->action(
            'edit_user_profile_update',
            [$this->profile, 'save']
        )->dispatch();
    }

    /**
     * Dispach profile to the view.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->hook->action(
            'admin_print_footer_scripts',
            [$this, 'script']
        )->dispatch();

        echo $this->view->make('Assely::Profile/profile', [
            'title' => $this->profile->getModel()->getSingular(),
            'description' => $this->profile->getModel()->getArgument('description'),
            'slug' => $this->profile->getModel()->getSlug(),
            'fingerprint' => $this->profile->getModel()->getFingerprint(),
        ]);
    }

    /**
     * Render profile script.
     *
     * @return void
     */
    public function script()
    {
        echo $this->view->make('Assely::script', [
            'slug' => $this->profile->getModel()->getSlug(),
            'fingerprint' => $this->profile->getModel()->getFingerprint(),
            'fields' => json_encode($this->profile->getFields()->all()),
        ]);
    }
}
