<?php

use Assely\Adapter\Menu;
use Assely\Config\ApplicationConfig;
use Brain\Monkey\Functions;

class MenuTest extends TestCase
{
    public function test_menu_adapter_touched_properties()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $timestamp = strtotime('1997-07-16 19:20:00');

        Functions::expect('get_option')->with('date_format')->andReturn('dd.mm.yyyy');
        Functions::expect('date_i18n')->with('dd.mm.yyyy', $timestamp)->andReturn('16.07.1997');

        $this->assertEquals('Attr title', $menu->attr);
        $this->assertEquals(['current_page-item', 'menu-item', 'menu-current-item'], $menu->classes);
        $this->assertEquals('Item description', $menu->description);
        $this->assertEquals(1, $menu->id);
        $this->assertEquals(3, $menu->item_id);
        $this->assertEquals('Item type', $menu->item_type);
        $this->assertEquals('http://example.com/link', $menu->link);
        $this->assertEquals('16.07.1997', $menu->modified_at);
        $this->assertEquals(10, $menu->order);
        $this->assertEquals(2, $menu->parent_id);
        $this->assertEquals('Item target', $menu->target);
        $this->assertEquals('Menu Item', $menu->title);
    }

    /**
     * @test
     */
    public function it_should_be_active_when_there_is_class_with_current_string()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $this->assertTrue($menu->active);

        $menu->classes = ['menu-item'];
        $this->assertFalse($menu->active);
    }

    /**
     * @test
     */
    public function test_access_to_the_menu_item_classes()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $this->assertEquals(['current_page-item', 'menu-item', 'menu-current-item'], $menu->classes);
    }

    /**
     * @test
     */
    public function test_children_setters()
    {
        $model = $this->getModel();

        $menu1 = $this->getMenu($model);
        $menu2 = $this->getMenu($model);

        $child1 = $this->getMenu($model);
        $child2 = $this->getMenu($model);

        $menu1->setChildren([$child1, $child2]);
        $this->assertCount(2, $menu1->children);
        $this->assertContainsOnlyInstancesOf(Menu::class, $menu1->children);

        $menu2->setChild($child1)->setChild($child2);
        $this->assertCount(2, $menu2->children);
        $this->assertContainsOnlyInstancesOf(Menu::class, $menu2->children);
    }

    /**
     * @test
     */
    public function test_setting_and_getting_the_menu_item_children()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $child1 = $this->getMenu($model);
        $child2 = $this->getMenu($model);

        $this->assertFalse($menu->hasChildren);

        $menu->setChildren([$child1, $child2]);

        $this->assertTrue($menu->hasChildren);
        $this->assertCount(2, $menu->children);
        $this->assertContainsOnlyInstancesOf(Menu::class, $menu->children);
    }

    /**
     * @test
     */
    public function test_converting_menu_adapter_instance_to_string()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $timestamp = strtotime($menu->modified_at);

        $this->assertEquals("Assely\Adapter\Menu/1-{$timestamp}", (string) $menu);
    }

    /**
     * @test
     */
    public function test_converting_menu_adapter_instance_to_json_and_array()
    {
        $model = $this->getModel();
        $menu = $this->getMenu($model);

        $this->assertEquals('{"active":true,"attr":"Attr title","classes":["current_page-item","menu-item","menu-current-item"],"description":"Item description","id":1,"item_id":3,"item_type":"Item type","link":"http:\/\/example.com\/link","modified_at":null,"order":10,"parent_id":2,"target":"Item target","title":"Menu Item"}', $menu->toJson());

        $this->assertEquals(['active' => true, 'attr' => 'Attr title', 'classes' => ['current_page-item', 'menu-item', 'menu-current-item'], 'description' => 'Item description', 'id' => 1, 'item_id' => 3, 'item_type' => 'Item type', 'link' => 'http://example.com/link', 'modified_at' => null, 'order' => 10, 'parent_id' => 2, 'target' => 'Item target', 'title' => 'Menu Item'], $menu->toArray());
    }

    public function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\MenuModel');
    }

    public function getMenu($model)
    {
        $config = new ApplicationConfig;

        $menu = new Menu($config);

        $menu
            ->setAdaptee(new WP_Menu)
            ->setModel($model);

        return $menu;
    }
}

class WP_Menu
{
    public $attr_title = 'Attr title';
    public $classes = ['current_page-item', 'menu-item', 'menu-current-item'];
    public $description = 'Item description';
    public $ID = 1;
    public $menu_item_parent = 2;
    public $menu_order = 10;
    public $object_id = 3;
    public $post_modified = '1997-07-16 19:20:00';
    public $target = 'Item target';
    public $title = 'Menu Item';
    public $type = 'Item type';
    public $url = 'http://example.com/link';
}
