<?php

namespace Assely\Pagination;

class PaginationItem
{
    /**
     * Pagination item entire markup.
     *
     * @var string
     */
    public $markup;

    /**
     * Item url.
     *
     * @var string
     */
    public $link;

    /**
     * Item type.
     *
     * @var string
     */
    public $type;

    /**
     * Item title.
     *
     * @var string
     */
    public $title;

    /**
     * Item number.
     *
     * @var int
     */
    public $number;

    /**
     * Item active status.
     *
     * @var bool
     */
    public $active = false;

    /**
     * Construct pagination Item.
     *
     * @param string $markup
     */
    public function __construct($markup)
    {
        $this->markup = $markup;

        $this->resolveType();
        $this->resolveLinkAndTitle();
    }

    /**
     * Resolve item type from the markup.
     *
     * @return void
     */
    public function resolveType()
    {
        $regrex = '/class=.+(?<type>dots|number).+/';

        preg_match($regrex, $this->markup, $matches);

        $this->type = $matches['type'];
    }

    /**
     * Resolve item type from the markup.
     *
     * @return void
     */
    public function resolveLinkAndTitle()
    {
        $regrex = "/(?:href=['\"](?<link>.+)['\"])?(?:>(?<title>.+)<)/";

        preg_match($regrex, $this->markup, $matches);

        $this->link = $matches['link'];
        $this->title = $matches['title'];
    }
}
