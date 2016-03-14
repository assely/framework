<?php

namespace Assely\Routing;

class WordpressConditions
{
    /**
     * WordPress conditional tags.
     *
     * @var array
     */
    protected $conditions = [
         'is_404' => '404',
         'is_home' => 'home',
         'is_front_page' => 'front',
         'is_page' => 'page',
         'is_single' => 'post',
         'is_sticky' => 'sticky',
         'is_singular' => 'single',
         'is_tax' => 'taxonomy',
         'is_tag' => 'tag',
         'is_archive' => 'archive',
         'is_post_type_archive' => 'list',
         'is_attachment' => 'attachment',
         'is_author' => 'user',
         'is_category' => 'category',
         'is_date' => 'date',
         'is_day' => 'day',
         'is_month' => 'month',
         'is_time' => 'time',
         'is_year' => 'year',
         'is_paged' => 'paged',
         'is_search' => 'search',
    ];

    /**
     * Add Wordpress condition.
     *
     * @param array $conditions
     *
     * @return array
     */
    public function add(array $conditions)
    {
        $this->conditions = array_merge($this->conditions, $conditions);

        return $this->conditions;
    }

    /**
     * Get all conditions.
     *
     * @return array
     */
    public function all()
    {
        return $this->conditions;
    }

    /**
     * Checks if passed condition is positive.
     *
     * @param  string  $condition
     * @param  array   $arguments
     *
     * @return boolean
     */
    public function is($condition, $arguments = [])
    {
        return call_user_func_array($this->{$condition}, $arguments);
    }

    /**
     * Get Wordpress condition.
     *
     * @param string $condition
     *
     * @return string|null
     */
    public function __get($condition)
    {
        // If we have requested condition return his
        // full name from the wp conditions map.
        if (in_array($condition, $this->conditions)) {
            return array_search($condition, $this->conditions);
        }
    }
}
