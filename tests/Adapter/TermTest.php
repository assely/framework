<?php

use Assely\Adapter\Term;
use Illuminate\Support\Collection;
use Assely\Config\ApplicationConfig;

class TermTest extends TestCase
{
    /**
     * @test
     */
    public function test_term_adapter_touched_properties()
    {
        $model = $this->getModel();
        $term = $this->getTerm($model);

        $this->assertEquals('15', $term->count);
        $this->assertEquals('Term description', $term->description);
        $this->assertEquals('group', $term->group);
        $this->assertEquals(2, $term->id);
        $this->assertEquals(1, $term->parent_id);
        $this->assertEquals('term', $term->slug);
        $this->assertEquals(10, $term->taxonomy_id);
        $this->assertEquals('taxonomy', $term->taxonomy_slug);
        $this->assertEquals('Term', $term->title);
    }

    /**
     * @test
     */
    public function test_getting_the_term_metadata()
    {
        $model = $this->getModel();
        $term = $this->getTerm($model);

        $model->shouldReceive('findMeta')->once()->with(2, 'key')->andReturn('key-metadata');
        $model->shouldReceive('getMeta')->once()->with(2)->andReturn('all-metadata');

        $this->assertEquals('key-metadata', $term->meta('key'));
        $this->assertEquals('all-metadata', $term->meta);
    }

    /**
     * @test
     */
    public function test_getting_a_post_of_the_term()
    {
        $model = $this->getModel();
        $term = $this->getTerm($model);

        $model->shouldReceive('postsWith')->once()->with($term, null, [])->andReturn(['post1', 'post2']);
        $model->shouldReceive('postsWith')->once()->with($term, 'posttype', [])->andReturn(['posttype-post1', 'posttype-post2']);

        $this->assertEquals(['post1', 'post2'], $term->posts());
        $this->assertEquals(['posttype-post1', 'posttype-post2'], $term->posts('posttype'));
    }

    /**
     * @test
     */
    public function test_converting_term_adapter_instance_to_json()
    {
        $model = $this->getModel();
        $term = $this->getTerm($model);

        $model->shouldReceive('getMeta')->once()->with(2)->andReturn(new Collection(['meta' => 'data']));

        $this->assertEquals('{"count":15,"description":"Term description","group":"group","id":2,"meta":{"meta":"data"},"parent_id":1,"slug":"term","taxonomy_id":10,"taxonomy_slug":"taxonomy","title":"Term"}', $term->toJson());
    }

    public function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\TermModel');
    }

    public function getTerm($model)
    {
        $config = new ApplicationConfig;

        $term = new Term($config);

        $term
            ->setAdaptee(new WP_Term)
            ->setModel($model);

        return $term;
    }
}

class WP_Term
{
    public $term_id = 2;
    public $name = 'Term';
    public $slug = 'term';
    public $term_group = 'group';
    public $term_taxonomy_id = 10;
    public $taxonomy = 'taxonomy';
    public $description = 'Term description';
    public $parent = 1;
    public $count = 15;
}
