<?php

namespace Assely\Posttype;

use Assely\Column\ColumnsCollection;
use Assely\Singularity\Singularity;
use Assely\Support\Accessors\HoldsColumns;

class PosttypeSingularity extends Singularity
{
    use HoldsColumns;

    /**
     * Base Wordpress post types.
     *
     * @var array
     */
    private $basePosttypes = ['post', 'page'];

    /**
     * Posttype manager.
     *
     * @var \Assely\Posttype\PosttypeManager
     */
    public $manager;

    /**
     * Construct postype.
     *
     * @param \Assely\Posttype\PosttypeManager $manager
     * @param \Assely\Column\ColumnsCollection $columns
     */
    public function __construct(
        PosttypeManager $manager,
        ColumnsCollection $columns
    ) {
        $this->manager = $manager;
        $this->columns = $columns;
    }

    /**
     * Set posttype columns columns.
     *
     * @param  array $columns
     *
     * @return self
     */
    public function columns(array $columns)
    {
        $this->columns->setColumns($columns);

        $this->manager->columns();

        return $this;
    }

    /**
     * Register singularity.
     *
     * @throws \Assely\Posttype\PosttypeException
     *
     * @return object|\WP_Error|null
     */
    public function register()
    {
        if (! $this->isBasePosttype()) {
            return $this->registerPosttype();
        }
    }

    /**
     * Register posttype.
     *
     * @return object|\WP_Error
     */
    protected function registerPosttype()
    {
        if (! $this->isRegistered()) {
            $parameters = array_merge(
                ['labels' => $this->model->getLabels()],
                $this->model->getArguments()
            );

            return register_post_type($this->model->getSlug(), $parameters);
        }

        throw new PosttypeException("Posttype [{$this->model->getSlug()}] already exsist.");
    }

    /**
     * Checks if post type is not one of Wordpress base post types.
     *
     * @return bool
     */
    public function isBasePosttype()
    {
        return in_array($this->getModel()->getSlug(), $this->basePosttypes);
    }

    /**
     * Check if post type is already registered.
     *
     * @return bool
     */
    public function isRegistered()
    {
        return post_type_exists($this->model->getSlug());
    }
}
