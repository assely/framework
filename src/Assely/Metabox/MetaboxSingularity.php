<?php

namespace Assely\Metabox;

use Assely\Contracts\Singularity\FillableFieldsInterface;
use Assely\Contracts\Singularity\ValidatesScreenInterface;
use Assely\Field\FieldsCollection;
use Assely\Singularity\Singularity;
use Assely\Singularity\Traits\BelongsToOther;
use Assely\Support\Accessors\HoldsFields;
use Assely\Support\Accessors\StoresValue;

class MetaboxSingularity extends Singularity implements ValidatesScreenInterface, FillableFieldsInterface
{
    use BelongsToOther, StoresValue, HoldsFields;

    /**
     * Metabox manager.
     *
     * @var \Assely\Metabox\MetaboxManager
     */
    protected $manager;

    /**
     * Construct Metabox
     *
     * @param \Assely\Metabox\MetaboxManager $manager
     * @param \Assely\Field\FieldsCollection $fields
     */
    public function __construct(
        MetaboxManager $manager,
        FieldsCollection $fields
    ) {
        $this->manager = $manager;
        $this->fields = $fields;
    }

    /**
     * Register metabox.
     *
     * @return void
     */
    public function register()
    {
        return add_meta_box(
            $this->model->getSlug(),
            $this->model->getSingular(),
            [$this, 'fill'],
            $this->belongsTo,
            $this->model->getArgument('location'),
            $this->model->getArgument('priority')
        );
    }

    /**
     * Fill metabox fields.
     *
     * @param \WP_Post|\WP_Comment $object
     * @param void
     */
    public function fill($object)
    {
        if ($this->screenIsComment()) {
            $this->model->setContext('comment');

            return parent::prepare($object->comment_ID);
        }

        return parent::prepare($object->ID);
    }

    /**
     * Set metabox fields.
     *
     * @param  array $fields
     *
     * @return self
     */
    public function fields($fields)
    {
        $this->fields->setSchema($fields);

        return $this;
    }

    /**
     * Is the current screen a posttype
     * where the metabox belongs to.
     *
     * @return boolean
     */
    public function isValidScreen()
    {
        return $this->screenIsComment() || $this->screenIsPosttype();
    }

    /**
     * Current screen is comment edit?
     *
     * @return boolean
     */
    protected function screenIsComment()
    {
        return get_current_screen()->base === 'comment';
    }

    /**
     * Current screen is posttype where metabox belongs to?
     *
     * @return boolean
     */
    protected function screenIsPosttype()
    {
        return in_array(get_current_screen()->post_type, $this->belongsTo);
    }
}
