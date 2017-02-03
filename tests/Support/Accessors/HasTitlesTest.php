<?php

use Brain\Monkey\Functions;

class HasTitlesTest extends TestCase
{
    /**
     * @test
     */
    public function setting_singular_and_plural_titles_from_slug_while_titles_are_not_passed()
    {
        $stub = $this->getTraitStub('kitty');

        $this->assertInstanceOf('HasTitlesTraitStub', $stub->setSingular());
        $this->assertEquals('Kitty', $stub->getSingular());

        $this->assertInstanceOf('HasTitlesTraitStub', $stub->setPlural());
        $this->assertEquals('Kitties', $stub->getPlural());
    }

    /**
     * @test
     */
    public function setting_titles_when_they_are_passed()
    {
        $stub = $this->getTraitStub('kitty');

        $stub->setSingular(['Dog', 'Dogs']);
        $stub->setPlural(['Dog', 'Dogs']);

        $this->assertEquals('Dog', $stub->getSingular());
        $this->assertEquals('Dogs', $stub->getPlural());
    }

    /**
     * @test
     */
    public function getting_labels_based_on_titles()
    {
        $stub = $this->getTraitStub('kitty');

        $stub->setSingular();
        $stub->setPlural();

        $this->assertEquals([
            'name' => 'Kitties',
            'singular_name' => 'Kitty',
            'add_new' => 'Add New Kitty',
            'add_new_item' => 'Add New Kitty',
            'edit_item' => 'Edit Kitty',
            'new_item' => 'New Kitty',
            'all_items' => 'All Kitties',
            'view_item' => 'View Kitty',
            'search_items' => 'Search Kitties',
            'not_found' => 'Kitties no found',
            'not_found_in_trash' => 'Kitties no found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Kitties',
        ], $stub->getLabels());
    }

    /**
     * @param $slug
     * @return mixed
     */
    protected function getTraitStub($slug = 'dummy-slug')
    {
        Functions::expect('sanitize_title')->once()->andReturn($slug);

        $stub = new HasTitlesTraitStub($slug);

        return $stub->setSlug($slug);
    }
}

class HasTitlesTraitStub
{
    use Assely\Support\Accessors\HasSlug;
    use Assely\Support\Accessors\HasTitles;
}
