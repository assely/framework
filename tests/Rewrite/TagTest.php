<?php

use Assely\Rewrite\Tag;
use Brain\Monkey\Functions;

class TagTest extends TestCase
{
    /**
     * @test
     */
    public function test_single_tag_adding()
    {
        $endpoint = new Tag;

        Functions::expect('add_rewrite_tag')->with('%tag%', 'regrex')->once();

        $endpoint->add('tag', 'regrex');
    }

    /**
     * @test
     */
    public function test_multiple_tags_adding()
    {
        $endpoint = new Tag;

        Functions::expect('add_rewrite_tag')->once()->with('%tag1%', 'regrex1');
        Functions::expect('add_rewrite_tag')->once()->with('%tag2%', 'regrex2');

        $endpoint->add([
            'tag1' => 'regrex1',
            'tag2' => 'regrex2',
        ]);
    }
}
