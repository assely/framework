<?php

namespace Assely\Pagination;

use Assely\Support\Accessors\HasArguments;

class Pagination
{
    use HasArguments;

    /**
     * Current page number.
     *
     * @var int|string
     */
    public $currentPage;

    /**
     * Next page instance.
     *
     * @var \Assely\Pagination\PaginationItem
     */
    public $next;

    /**
     * Previous page instance.
     *
     * @var \Assely\Pagination\PaginationItem
     */
    public $previous;

    /**
     * Collection of pagination items.
     *
     * @var \Assely\Pagination\PaginationItem[]
     */
    public $items = [];

    /**
     * Collection of pagination links.
     *
     * @var array
     */
    protected $links;

    /**
     * Default pagination arguments.
     *
     * @var array
     */
    protected $defaults = [
        'type' => 'array',
    ];

    /**
     * Get pagination.
     *
     * @param  int|string  $currentPage
     * @param  array  $arguments
     *
     * @return self
     */
    public function make($currentPage, array $arguments = [])
    {
        $this->currentPage = $currentPage ?: get_query_var('paged');

        $this->setArguments(array_merge($arguments, $this->getDefaults()));
        $this->setLinks($this->getPaginationLinks());

        return $this->generate();
    }

    /**
     * Get links for pagination.
     *
     * @return array
     */
    protected function getPaginationLinks()
    {
        return paginate_links(array_merge(
            $this->getArguments(),
            ['current' => $this->currentPage]
        ));
    }

    /**
     * Generate pagination.
     *
     * @return void
     */
    public function generate()
    {
        $this->generateItems();
        $this->findActiveItem();

        return $this;
    }

    /**
     * Generate pagination items, previous and next.
     *
     * @return void
     */
    public function generateItems()
    {
        // Iterator for pagination pages.
        $j = 1;

        foreach ($this->getLinks() as $index => $link) {
            // Create new pagination item
            $item = new PaginationItem($link);

            // If we on first link and currently active page is not first,
            // we know that this link is for previous pagination item.
            if ($index === 0 && $this->currentPage != 1) {
                $this->markAsPrevious($item);

                continue;
            }

            // If we on last link and currently active page is not last,
            // we know that this link is for next pagination item.
            if (
                $index === $this->getLinksCount(true)
                && $this->currentPage != $this->getLinksCount(true)
            ) {
                $this->markAsNext($item);

                break;
            }

            // We can't use loop index, because sometimes first and
            // last link are for the next and previous item, so use
            // external iterator to index pages of pagination.
            $item->number = $j++;

            // Push pagination pages to items collection.
            $this->items[] = $item;
        }
    }

    /**
     * Find and mark current page item.
     *
     * @return void
     */
    public function findActiveItem()
    {
        foreach ($this->items as $index => $item) {
            $index++;

            if ($item->number == $this->currentPage) {
                $item->active = true;
            }
        }
    }

    /**
     * Mark item as pagination previous page.
     *
     * @param  \Assely\Pagination\PaginationItem $item
     *
     * @return \Assely\Pagination\PaginationItem
     */
    public function markAsPrevious($item)
    {
        $item->type = 'previous';

        $this->previous = $item;

        return $item;
    }

    /**
     * Mark item as pagination next page.
     *
     * @param  \Assely\Pagination\PaginationItem $item
     *
     * @return \Assely\Pagination\PaginationItem
     */
    public function markAsNext($item)
    {
        $item->type = 'next';

        $this->next = $item;

        return $item;
    }

    /**
     * Gets the value of links.
     *
     * @return mixed
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Get number of links.
     *
     * @param  bool $zeroBased Using zero-based index
     *
     * @return int
     */
    public function getLinksCount($zeroBased = false)
    {
        $count = count($this->getLinks());

        if ($zeroBased) {
            return $count - 1;
        }

        return $count;
    }

    /**
     * Sets the value of links.
     *
     * @param mixed $links the links
     *
     * @return self
     */
    public function setLinks($links)
    {
        $this->links = $links;

        return $this;
    }
}
