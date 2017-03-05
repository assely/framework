<?php

use Assely\Pagination\Pagination;
use Assely\Pagination\PaginationItem;
use Brain\Monkey\Functions;

class PaginationTest extends TestCase
{
    protected $outputStart = [
        '<span class="page-numbers current">1</span>',
        '<a class="page-numbers" href="http://site.com/page/2">2</a>',
        '<a class="page-numbers" href="http://site.com/page/3">3</a>',
        '<a class="page-numbers" href="http://site.com/page/4">4</a>',
        '<a class="page-numbers" href="http://site.com/page/5">5</a>',
        '<span class="page-numbers dots">…</span>',
        '<a class="page-numbers" href="http://site.com/page/10">10</a>',
        '<a class="next page-numbers" href="http://site.com/page/4">Next »</a>',
    ];

    protected $outputMiddle = [
        '<a class="prev page-numbers" href="http://site.com/page/2">« Previous</a>',
        '<a class="page-numbers" href="http://site.com">1</a>',
        '<a class="page-numbers" href="http://site.com/page/2">2</a>',
        '<span class="page-numbers current">3</span>',
        '<a class="page-numbers" href="http://site.com/page/4">4</a>',
        '<a class="page-numbers" href="http://site.com/page/5">5</a>',
        '<span class="page-numbers dots">…</span>',
        '<a class="page-numbers" href="http://site.com/page/10">10</a>',
        '<a class="next page-numbers" href="http://site.com/page/4">Next »</a>',
    ];

    public function setUp()
    {
        $this->pagination = $this->getPagination();

        parent::setUp();
    }

    public function test_getting_a_pagination_without_passing_current_paged()
    {
        $this->expectPagedFromQuery();

        $this->pagination->make();

        $this->makeAssertions();
    }

    public function makeAssertions()
    {
        $this->assertCount(7, $this->pagination->items);
        $this->assertContainsOnlyInstancesOf(PaginationItem::class, $this->pagination->items);
        $this->assertTrue($this->pagination->items[2]->active);
        $this->assertEquals('dots', $this->pagination->items[5]->type);
    }

    protected function expectPagedFromQuery()
    {
        Functions::expect('get_query_var')->once()->with('paged')->andReturn(3);
        Functions::expect('paginate_links')->once()->with(['current' => 3, 'type' => 'array'])->andReturn($this->output);
    }

    protected function expectPagedFromArgument()
    {
        Functions::expect('get_query_var')->never();
        Functions::expect('paginate_links')->once()->with(['current' => 3, 'type' => 'array'])->andReturn($this->output);
    }

    protected function getPagination()
    {
        return new Pagination;
    }
}
