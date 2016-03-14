<?php
namespace Assely\Taxonomy;

use Assely\Column\ColumnsCollection;
use Assely\Contracts\Singularity\FillableFieldsInterface;
use Assely\Contracts\Singularity\ValidatesScreenInterface;
use Assely\Field\FieldsCollection;
use Assely\Singularity\Singularity;
use Assely\Singularity\Traits\BelongsToOther;
use Assely\Support\Accessors\HoldsColumns;
use Assely\Support\Accessors\HoldsFields;
use Assely\Support\Accessors\StoresValue;

class TaxonomySingularity extends Singularity implements ValidatesScreenInterface, FillableFieldsInterface
{
    use BelongsToOther, StoresValue, HoldsFields, HoldsColumns;

    /**
     * Base Wordpress taxonomies.
     *
     * @var array
     */
    private $baseTaxonomies = ['category', 'post_tag'];

    /**
     * Taxonomy manager.
     *
     * @var \Assely\Taxonomy\TaxonomyManager
     */
    protected $manager;

    /**
     * Construct taxonomy.
     *
     * @param \Assely\Taxonomy\TaxonomyManager $manager
     * @param \Assely\Field\FieldsCollection $fields
     * @param \Assely\Column\ColumnsCollection $columns
     */
    public function __construct(
        TaxonomyManager $manager,
        FieldsCollection $fields,
        ColumnsCollection $columns
    ) {
        $this->manager = $manager;
        $this->fields = $fields;
        $this->columns = $columns;
    }

    /**
     * Set taxonomy fields.
     *
     * @param  array $fields
     *
     * @return self
     */
    public function fields(array $fields)
    {
        $this->getFields()->setSchema($fields);

        return $this;
    }

    /**
     * Set taxonomy list columns.
     *
     * @param  array $columns
     *
     * @return self
     */
    public function columns(array $columns)
    {
        $this->getColumns()->setColumns($columns);

        $this->manager->columns();

        return $this;
    }

    /**
     * Register singularity.
     *
     * @return null|\WP_Error
     */
    public function register()
    {
        if ( ! $this->isBaseTaxonomy()) {
            return $this->registerTaxonomy();
        }
    }

    /**
     * Register taxonomy.
     *
     * @return null|\WP_Error
     */
    protected function registerTaxonomy()
    {
        if ( ! $this->isRegistered()) {
            $parameters = array_merge([
                'labels' => $this->getModel()->getLabels(),
                'rewrite' => ['slug' => $this->getModel()->getSlug()],
            ], $this->getModel()->getArguments());

            return register_taxonomy(
                $this->getModel()->getSlug(),
                $this->getBelongsTo(),
                $parameters
            );
        }

        throw new TaxonomyException("Taxonomy [{$this->model->getSlug()}] already exsist.");
    }

    /**
     * Fill taxonomy fields.
     *
     * @param mixed $term
     *
     * @return void
     */
    public function fill($term)
    {
        $id = (isset($term->term_id)) ? $term->term_id : false;

        parent::prepare($id);
    }

    /**
     * Check if current screen is taxonomy model.
     *
     * @return boolean
     */
    public function isValidScreen()
    {
        return get_current_screen()->taxonomy === $this->getModel()->getSlug();
    }

    /**
     * Checks if taxonomy is not one of Wordpress base taxonomies.
     *
     * @return boolean
     */
    public function isBaseTaxonomy()
    {
        return in_array($this->getModel()->getSlug(), $this->baseTaxonomies);
    }

    /**
     * Checks if taxonomy is already registered.
     *
     * @return boolean
     */
    public function isRegistered()
    {
        return taxonomy_exists($this->getModel()->getSlug());
    }
}
