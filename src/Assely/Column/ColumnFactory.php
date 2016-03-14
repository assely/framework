<?php

namespace Assely\Column;

use Assely\Foundation\Factory;

class ColumnFactory extends Factory
{
    /**
     * Create taxonomy column.
     *
     * @param string $name
     *
     * @return \Assely\Column\ColumnTaxonomy
     */
    public function taxonomy($name)
    {
        $taxonomy = $this->container->make($name);

        return new ColumnTaxonomy($taxonomy);
    }

    /**
     * Create metabox column.
     *
     * @param string $metabox
     *
     * @return \Assely\Column\ColumnMetabox
     */
    public function metabox($metabox, $field)
    {
        return new ColumnMetabox(
            $this->container->make($metabox),
            $field
        );
    }

    /**
     * Create term column.
     *
     * @param string $term
     *
     * @return \Assely\Column\ColumnTerm
     */
    public function term($term, $field)
    {
        return new ColumnTerm(
            $this->container->make($term),
            $field
        );
    }

    /**
     * Create profile column.
     *
     * @param string $profile
     * @param string $field
     *
     * @return \Assely\Column\ColumnProfile
     */
    public function profile($profile, $field)
    {
        return new ColumnProfile(
            $this->container->make($profile),
            $field
        );
    }
}
