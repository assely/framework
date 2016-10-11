<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\PostAdapter;
use Assely\Adapter\TermAdapter;
use Assely\Contracts\Singularity\WPQueryable;
use Assely\Nonce\NonceFactory;
use Assely\Singularity\Model\CommentModel;
use Assely\Singularity\Model\TaxonomyModel;

class PosttypeModel extends MetaboxModel implements WPQueryable
{
    /**
     * Nonce factory instance.
     *
     * @var \Assely\Nonce\NonceFactory
     */
    protected $nonce;

    /**
     * Taxonomy model instance.
     *
     * @var \Assely\Singularity\Model\TaxonomyModel
     */
    protected $taxonomy;

    /**
     * Comment model instance.
     *
     * @var \Assely\Singularity\Model\CommentModel
     */
    protected $comment;

    /**
     * Default post type arguments.
     *
     * @var array
     */
    protected $defaults = [
        // Wordpress specific
        'public' => true,
        'publicly_queryable' => true,
        'query_var' => true,
        'rewrite' => false,
        'capability_type' => 'post',
        'supports' => ['title', 'editor', 'thumbnail', 'comments'],
        'hierarchical' => false,
        'menu_position' => null,
        'has_archive' => true,

        // Assely specific
        'title' => [],
        'description' => [],
        'preserve' => 'multiple',
    ];

    /**
     * Query default arguments.
     *
     * @var array
     */
    protected $queryDefaults = [
        'paged' => 1,
    ];

    /**
     * Construct posttype model.
     *
     * @param \Assely\Nonce\NonceFactory $nonce
     * @param \Assely\Singularity\Model\TaxonomyModel $taxonomy
     * @param \Assely\Singularity\Model\CommentModel $comment
     */
    public function __construct(
        NonceFactory $nonce,
        TaxonomyModel $taxonomy,
        CommentModel $comment
    ) {
        $this->taxonomy = $taxonomy;
        $this->comment = $comment;

        parent::__construct($nonce);
    }

    /**
     * Query posttype.
     *
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function query(array $arguments = [])
    {
        $defaults = array_merge($this->queryDefaults, ['post_type' => $this->getSlug()]);

        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(PostAdapter::class)
            ->plugIn(get_posts(array_merge($arguments, $defaults)))
            ->getConnected();
    }

    /**
     * Find post by id.
     *
     * @param integer $id
     *
     * @return \Assely\Adapter\PostAdapter
     */
    public function find($id)
    {
        if (! is_numeric($id) && is_string($id)) {
            return $this->findBySlug($id);
        }

        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(PostAdapter::class)
            ->plugIn(get_post($id))
            ->getConnected()
            ->first();
    }

    /**
     * Find post by slug.
     *
     * @param string $slug
     *
     * @return \Assely\Adapter\PostAdapter
     */
    public function findBySlug($slug)
    {
        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(PostAdapter::class)
            ->plugIn(get_page_by_path($slug, OBJECT, $this->getSlug()))
            ->getConnected()
            ->first();
    }

    /**
     * Find post by id or trow if unsuccessful.
     *
     * @param integer $id
     *
     * @return \Assely\Adapter\PostAdapter
     */
    public function findOrFail($id)
    {
        $post = $this->find($id);

        if (! $post->getWrappedObject()) {
            throw new QueryException("Posttype [{$this->slug}] could not find post with id: {$id}.");
        }

        return $post;
    }

    /**
     * Get all posts.
     *
     * @return array
     */
    public function all()
    {
        return $this->query([
            'nopaging' => true,
        ])->all();
    }

    /**
     * Paginate query results.
     *
     * @param  integer|string $page
     * @param  integer|string $perPage
     *
     * @return self
     */
    public function paginate($page, $perPage = null)
    {
        $this->queryDefaults['paged'] = $page;
        $this->queryDefaults['posts_per_page'] = $perPage ?: get_option('posts_per_page');

        return $this;
    }

    /**
     * Get post by slug.
     *
     * @param string $slug
     *
     * @return \Assely\Adapter\PostAdapter
     */
    public function getBySlug($slug)
    {
        return $this->query([
            'name' => $slug,
        ])->first();
    }

    /**
     * Gets post terms.
     *
     * @param  integer $id
     * @param  string $taxonomy_slug
     * @param  array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTerms(PostAdapter $post, $taxonomy)
    {
        return $this->taxonomy
            ->make($taxonomy)
            ->postTerms($post);
    }

    /**
     * Get posts with term.
     *
     * @param \Assely\Adapter\TermAdapter $term
     *
     * @return \Illuminate\Support\Collection
     */
    public function withTerm(TermAdapter $term)
    {
        return $this->query([
            'tax_query' => [
                [
                    'taxonomy' => $term->taxonomy_slug,
                    'field' => 'slug',
                    'terms' => $term->slug,
                ],
            ],
        ]);
    }

    /**
     * Gets post comments.
     *
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function getComments(array $arguments)
    {
        return $this->comment
            ->make('comment')
            ->query($arguments);
    }

    /**
     * Create post.
     *
     * @return boolean
     */
    public function create(array $arguments)
    {
        $post = $this->mockAdapterPost($arguments);

        return wp_insert_post(array_merge(
            (array) $post->getAdaptee(),
            ['post_type' => $this->getSlug()]
        ));
    }

    /**
     * Create post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return boolean|\WP_Error
     *
     */
    public function createOrFail(array $arguments)
    {
        $status = $this->create($arguments);

        if (is_wp_error($status)) {
            throw new QueryException("Posttype [{$this->slug}] could not create new post.");
        }

        return $status;
    }

    /**
     * Update post.
     *
     * @param  integer $id
     * @param  array $arguments
     *
     * @return integer
     */
    public function update(PostAdapter $post)
    {
        return wp_update_post($post->getAdaptee());
    }

    /**
     * Update post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return boolean|\WP_Error
     *
     */
    public function updateOrFail(PostAdapter $post)
    {
        $status = $this->update($post);

        if (is_wp_error($status)) {
            throw new QueryException("Posttype [{$this->slug}] could not update post with id: {$id}.");
        }

        return $status;
    }

    /**
     * Mock post adapter.
     *
     * @param array $parameters
     *
     * @return \Assely\Adapter\PostAdapter
     */
    public function mockAdapterPost(array $parameters)
    {
        $post = $this->plugAdapter(PostAdapter::class, new \WP_Post(new \StdClass))->first();

        foreach ($parameters as $key => $value) {
            $post->{$key} = $value;
        }

        return $post;
    }

    /**
     * Trash post.
     *
     * @param  integer $id
     *
     * @return mixed
     */
    public function trash($id)
    {
        return wp_trash_post($id);
    }

    /**
     * Delete post.
     *
     * @param  integer  $id
     * @param  boolean $force
     *
     * @return mixed
     */
    public function delete($id, $force = false)
    {
        return wp_delete_post($id, $force);
    }

    /**
     * Delete post completly.
     *
     * @param  integer  $id
     * @param  boolean $force
     *
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->delete($id, true);
    }

    /**
     * Set model type.
     *
     * @param  string $slug
     *
     * @return self
     */
    public function type($slug)
    {
        return $this->setSlug($slug);
    }
}
