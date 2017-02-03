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

        Functions::expect('add_rewrite_tag')->once()->andReturn(null);

        $endpoint->add('tag', 'regrex');
    }

    /**
     * @test
     */
    public function test_multiple_tags_adding()
    {
        $endpoint = new Tag;

        Functions::expect('add_rewrite_tag')->twice()->andReturn(null);

        $endpoint->add([
            'tag1' => 'regrex1',
            'tag2' => 'regrex2',
        ]);
    }
}
