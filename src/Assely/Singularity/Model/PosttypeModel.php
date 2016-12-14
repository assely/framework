<?php

namespace Assely\Singularity\Model;

use Assely\Adapter\Post;
use Assely\Adapter\Term;
use Assely\Support\Facades\App;
use Assely\Contracts\Singularity\WPQueryable;

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
        'rewrite' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'comments'],
        'menu_position' => 20,
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
     * Query posttype.
     *
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function query(array $arguments = [])
    {
        $defaults = array_merge($this->queryDefaults, [
            'post_type' => $this->getSlug(),
        ]);

        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(Post::class)
            ->plugIn(get_posts(array_merge($arguments, $defaults)))
            ->getConnected();
    }

    /**
     * Find post by id.
     *
     * @param int $id
     *
     * @return \Assely\Adapter\Post
     */
    public function find($id)
    {
        if (! is_numeric($id) && is_string($id)) {
            return $this->findBySlug($id);
        }

        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(Post::class)
            ->plugIn(get_post($id))
            ->getConnected()
            ->first();
    }

    /**
     * Find post by slug.
     *
     * @param string $slug
     *
     * @return \Assely\Adapter\Post
     */
    public function findBySlug($slug)
    {
        return $this->getAdapterPlugger()
            ->setModel($this)
            ->setAdapter(Post::class)
            ->plugIn(get_page_by_path($slug, OBJECT, $this->getSlug()))
            ->getConnected()
            ->first();
    }

    /**
     * Find post by id or trow if unsuccessful.
     *
     * @param int $id
     *
     * @return \Assely\Adapter\Post
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
     * @param  int|string $page
     * @param  int|string $perPage
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
     * @return \Assely\Adapter\Post
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
     * @param  int $id
     * @param  string $taxonomy_slug
     * @param  array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTerms(Post $post, $taxonomy)
    {
        $model = App::make(TaxonomyModel::class);

        return $model->make($taxonomy)->postTerms($post);
    }

    /**
     * Gets all post terms.
     *
     * @param \Assely\Adapter\Post $post
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllTerms(Post $post)
    {
        $taxonomies = get_object_taxonomies($this->getSlug());

        $adaptees = wp_get_object_terms($post->id, $taxonomies);

        return $this->plugAdapter(Term::class, $adaptees);
    }

    /**
     * Get posts with term.
     *
     * @param \Assely\Adapter\Term $term
     * @param array $arguments
     *
     * @return \Illuminate\Support\Collection
     */
    public function withTerm(Term $term, $arguments = [])
    {
        $parameters = array_merge($arguments, [
            'tax_query' => [
                [
                    'taxonomy' => $term->taxonomy_slug,
                    'field' => 'slug',
                    'terms' => $term->slug,
                ],
            ],
        ]);

        return $this->query($parameters);
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
        $model = App::make(CommentModel::class);

        return $model->make('comment')->query($arguments);
    }

    /**
     * Create post.
     *
     * @return bool
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
     * @return bool|\WP_Error
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
     * @param  int $id
     * @param  array $arguments
     *
     * @return int
     */
    public function update(Post $post)
    {
        return wp_update_post($post->getAdaptee());
    }

    /**
     * Update post or fail if unsuccessful.
     *
     * @throws QueryException
     *
     * @return bool|\WP_Error
     */
    public function updateOrFail(Post $post)
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
     * @return \Assely\Adapter\Post
     */
    public function mockAdapterPost(array $parameters)
    {
        $post = $this->plugAdapter(Post::class, new \WP_Post(new \StdClass))->first();

        foreach ($parameters as $key => $value) {
            $post->{$key} = $value;
        }

        return $post;
    }

    /**
     * Trash post.
     *
     * @param  int $id
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
     * @param  int  $id
     * @param  bool $force
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
     * @param  int  $id
     * @param  bool $force
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
