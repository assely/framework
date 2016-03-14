<?php

namespace Assely\Contracts\Field;

use Assely\Field\FieldManager;
use Assely\Field\FieldsCollection;
use Illuminate\Contracts\View\Factory as ViewFactory;

interface FieldInterface
{
    /**
     * Construct field.
     *
     * @param \Assely\Field\FieldManager $manager
     * @param \Assely\Field\FieldsCollection $children
     * @param array $paths
     * @param string $type
     * @param string $slug
     * @param array $arguments
     */
    public function __construct(
        FieldManager $manager,
        FieldsCollection $children
    );

    /**
     * Field template.
     *
     * @param  \Illuminate\View\Factory $view
     *
     * @return void
     */
    public function template(ViewFactory $view);
}
