<?php

namespace Assely\Singularity\Model;

class ProfileModel extends UserModel
{
    /**
     * Profile default arguments.
     *
     * @var array
     */
    protected $defaults = [
        'title' => [],
        'description' => '',
        'preserve' => 'single',
    ];
}
