<?php

namespace Assely\Profile;

use Assely\Contracts\Singularity\FillableFieldsInterface;
use Assely\Contracts\Singularity\ValidatesScreenInterface;
use Assely\Field\FieldsCollection;
use Assely\Singularity\Singularity;
use Assely\Singularity\Traits\BelongsToOther;
use Assely\Support\Accessors\HoldsFields;
use Assely\Support\Accessors\StoresValue;

class ProfileSingularity extends Singularity implements ValidatesScreenInterface, FillableFieldsInterface
{
    use BelongsToOther, StoresValue, HoldsFields;

    /**
     * Construct profile.
     *
     * @param \Assely\Profile\ProfileManager $manager
     * @param \Assely\Field\FieldsCollection $fields
     */
    public function __construct(
        ProfileManager $manager,
        FieldsCollection $fields
    ) {
        $this->manager = $manager;
        $this->fields = $fields;
    }

    /**
     * Set profile fields.
     *
     * @param  array $field
     *
     * @return self
     */
    public function fields($fields)
    {
        $this->fields->setSchema($fields);

        return $this;
    }

    /**
     * Fill profile fields.
     *
     * @param  \WP_User $user
     *
     * @return void
     */
    public function fill($user)
    {
        parent::prepare($user->ID);
    }

    /**
     * Check if we are on user profile
     * or user edit screen.
     *
     * @return bool
     */
    public function isValidScreen()
    {
        return $this->currentScreenIsProfile() || $this->currentScreenIsUserEdit();
    }

    /**
     * Check if current screen
     * is profile page.
     *
     * @return bool
     */
    public function currentScreenIsProfile()
    {
        return get_current_screen()->base === 'profile';
    }

    /**
     * Check if current screen
     * is user edit page.
     *
     * @return bool
     */
    public function currentScreenIsUserEdit()
    {
        return get_current_screen()->base === 'user-edit';
    }
}
