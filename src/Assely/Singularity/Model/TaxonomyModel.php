<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\PostAdapter;
use Assely\Adapter\TermAdapter;
use Assely\Contracts\Singularity\WPQueryable;
use Assely\Singularity\QueryException;

class TaxonomyModel extends TermModel implements WPQueryable
{
    /**
     * Default taxonomy arguments
     *
     * @var array
     */
    protected $defaults = [
        // Wordpress specific
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,

        // Assely specific
        'title' => [],
        'description' => '',
        'preserve' => 'multiple',
    ];

    /**
     * Query
     *
     * @param  array $arguments
     *
     * @return mixed
     */
    public function query(array $arguments = [])
    {
        $adaptees = get_terms(['taxonomy' => $this->getSlug()]);

        return $this->plugAdapter(TermAdapter::class, $adaptees);
    }

    /**
     * Find term by key.
     *
     * @param  string $key
     * @param  integer $id
     *
     * @return \Illuminate\Support\Collection
     */
    public function findBy($key, $id)
    {
        $adaptees = get_term_by($key, $id, $this->getSlug());

        return $this->plugAdapter(TermAdapter::class, $adaptees)->first();
    }

    /**
     * Get post terms.
     *
     * @param \Assely\Adapter\PostAdapter $post
     *
     * @return \Illuminate\Support\Collection
     */
    public function postTerms(PostAdapter $post)
    {
        $adaptees = wp_get_post_terms($post->id, $this->getSlug());

        return $this->plugAdapter(TermAdapter::class, $adaptees);
    }

    /**
     * Get posts with term.
     *
     * @param \Assely\Adapter\TermAdapter $term
     *
     * @return \Illuminate\Support\Collection
     */
    public function postsWith(TermAdapter $term, $posttype)
    {
        return app('model.posttype')->make($posttype)->withTerm($term);
    }

    /**
     * Find term.
     *
     * @param  string $key
     * @param  integer $id
     *
     * @return \Illuminate\Support\Collection
     */
    public function find($id)
    {
        if (is_numeric($id)) {
            return $this->findBy('id', $id);
        }

        return $this->findBy('slug', $id);
    }

    /**
     * Find term or trow if unssuccesful.
     *
     * @param  integer $id     Term id
     * @param  string $output
     * @param  string $filter
     *
     * @return WP_Term
     */
    public function findOrFail($id)
    {
        $term = $this->find($id);

        if ( ! $term->getWrappedObject()) {
            throw new QueryException("Taxonomy [{$this->slug}] could not find term [{$id}].");
        }

        return $term;
    }

    /**
     * Get all taxonomy terms.
     *
     * @return array
     */
    public function all()
    {
        return $this->query()->all();
    }

    /**
     * Set model type.
     *
     * @param string $slug
     *
     * @return self
     */
    public function type($slug)
    {
        $this->setSlug($slug);

        return $this;
    }

    /**
     * Create term.
     *
     * @param  array $arguments
     *
     * @return array|\WP_Error
     */
    public function create(array $arguments)
    {
        return wp_insert_term($arguments['name'], $this->getSlug(), $arguments);
    }

    /**
     * Create term.
     *
     * @param  array $arguments
     *
     * @return array
     */
    public function createOrFail(array $arguments)
    {
        $term = $this->create($arguments);

        if (is_wp_error($term)) {
            throw new QueryException("Taxonomy [{$this->slug}] could not create new term.");
        }

        return $term;
    }

    /**
     * Update term.
     *
     * @param  integer $id
     * @param  array  $arguments
     *
     * @return array|\WP_Error
     */
    public function update(TermAdapter $term)
    {
        return wp_update_term($term->id, $this->getSlug(), (array) $term->getAdaptee());
    }

    /**
     * Update term.
     *
     * @param  integer $id
     * @param  array  $arguments
     *
     * @return array|\WP_Error
     */
    public function updateOrFail(TermAdapter $term)
    {
        $status = $this->update($term);

        if (is_wp_error($status)) {
            throw new QueryException("Taxonomy [{$this->slug}] could not update term.");
        }

        return $status;
    }

    /**
     * Delete term.
     *
     * @param  integer $id
     *
     * @return boolean|\WP_Error
     */
    public function delete($id)
    {
        return wp_delete_term($id, $this->getSlug());
    }
}
