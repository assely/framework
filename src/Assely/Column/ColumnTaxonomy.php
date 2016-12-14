<?php

namespace Assely\Column;

use Assely\Support\Facades\Post;
use Assely\Support\Facades\View;
use Assely\Support\Accessors\HasSlug;
use Assely\Support\Accessors\HasTitles;
use Assely\Support\Accessors\HasArguments;

class ColumnTaxonomy extends Column
{
    use HasSlug, HasArguments, HasTitles;

    /**
     * Construct taxonomy column.
     *
     * @param \Assely\Taxonomy\Taxonomy $taxonomy
     */
    public function __construct($taxonomy)
    {
        $this->taxonomy = $taxonomy;

        $this->setSlug($taxonomy->slug);
        $this->setArguments($taxonomy->arguments());
        $this->setSingular($taxonomy->getModel()->getArgument('title'));
    }

    /**
     * Render column content.
     *
     * @param int $id
     *
     * @return void
     */
    public function render($id)
    {
        $post = Post::find($id);

        View::make('Assely::Column/Taxonomy', [
            'terms' => $this->taxonomy->postTerms($post),
        ]);
    }
}
