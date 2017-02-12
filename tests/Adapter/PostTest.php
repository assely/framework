<?php

use Assely\Adapter\Post;
use Brain\Monkey\Functions;

class PostTest extends TestCase
{
    /**
     * @test
     */
    public function test_post_adapter_touched_properties()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        Functions::expect('get_option')->with('date_format')->andReturn('dd.mm.yyyy');
        Functions::expect('date_i18n')->with('dd.mm.yyyy', strtotime('1997-07-16 19:20:00'))->andReturn('16.07.1997');

        $this->assertEquals('Post Name', $post->title);
        $this->assertEquals('1', $post->author);
        $this->assertEquals('10', $post->comment_count);
        $this->assertEquals('open', $post->comment_status);
        $this->assertEquals('Post Content', $post->content);
        $this->assertEquals('16.07.1997', $post->created_at);
        $this->assertEquals('Post Excerpt', $post->excerpt);
        $this->assertEquals(1, $post->id);
        $this->assertEquals('0', $post->menu_order);
        $this->assertEquals('mime', $post->mime_type);
        $this->assertEquals('16.07.1997', $post->modified_at);
        $this->assertEquals(0, $post->parent_id);
        $this->assertEquals('password', $post->password);
        $this->assertEquals('ping-url', $post->ping);
        $this->assertEquals('open', $post->ping_status);
        $this->assertEquals('pinged-url', $post->pinged);
        $this->assertEquals('post-name', $post->slug);
        $this->assertEquals('draft', $post->status);
        $this->assertEquals('Post Name', $post->title);
        $this->assertEquals('post', $post->type);
    }

    /**
     * @test
     */
    public function test_getting_link_to_the_post()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        Functions::expect('get_permalink')->with(1)->andReturn('http://example.com/post-link');

        $this->assertEquals('http://example.com/post-link', $post->link);
    }

    /**
     * @test
     */
    public function test_destroying_of_the_post()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        $model->shouldReceive('delete')->with(1)->andReturn($post);

        $this->assertEquals($post, $post->destroy());
    }

    public function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\PosttypeModel');
    }

    public function getPost($model) {
        $post = new Post;

        $post
            ->setAdaptee(new WP_Post)
            ->setModel($model)
            ->connect();

        return $post;
    }
}

class WP_Post
{
    public $ID = 1;
    public $post_author = '1';
    public $post_name = 'post-name';
    public $post_type = 'post';
    public $post_mime_type = 'mime';
    public $post_title = 'Post Name';
    public $post_date = '1997-07-16 19:20:00';
    public $post_date_gmt = '1997-07-16 19:20:00';
    public $post_content = 'Post Content';
    public $post_excerpt = 'Post Excerpt';
    public $post_status = 'draft';
    public $comment_status = 'open';
    public $to_ping = 'ping-url';
    public $ping_status = 'open';
    public $pinged = 'pinged-url';
    public $post_password = 'password';
    public $post_parent = 0;
    public $post_modified = '1997-07-16 19:20:00';
    public $post_modified_gmt = '1997-07-16 19:20:00';
    public $comment_count = '10';
    public $menu_order = '0';
}