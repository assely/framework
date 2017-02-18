<?php

use Assely\Adapter\Post;
use Brain\Monkey\Functions;
use Assely\Config\ApplicationConfig;

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
    public function test_getting_the_post_metadata()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        $model->shouldReceive('findMeta')->once()->with(1, 'key')->andReturn('key-metadata');
        $model->shouldReceive('getMeta')->once()->with(1)->andReturn('all-metadata');

        $this->assertEquals('key-metadata', $post->meta('key'));
        $this->assertEquals('all-metadata', $post->meta);
    }

    /**
     * @test
     */
    public function test_getting_the_post_thumbnail()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        Functions::expect('get_post_thumbnail_id')->with(1)->andReturn(10);

        $thumbnail = $post->thumbnail;

        $this->assertInstanceOf('Assely\Thumbnail\Image', $thumbnail);
        $this->assertEquals(10, $thumbnail->id);
        $this->assertEquals('thumbnail', $thumbnail->size);
    }

    /**
     * @test
     */
    public function test_getting_the_post_terms()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        $model->shouldReceive('getTerms')->once()->with($post, 'taxonomy', [])->andReturn('tax-terms');
        $model->shouldReceive('getTerms')->once()->with($post, 'taxonomy', ['arguments'])->andReturn('tax-terms-with-argument');
        $model->shouldReceive('getAllTerms')->once()->with($post)->andReturn('all-terms');

        $this->assertEquals('tax-terms', $post->terms('taxonomy'));
        $this->assertEquals('tax-terms-with-argument', $post->terms('taxonomy', ['arguments']));
        $this->assertEquals('all-terms', $post->terms);
    }

    /**
     * @test
     */
    public function test_getting_the_post_comments()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        $model->shouldReceive('findMeta')->with(1, '_wp_page_template')->andReturn('template-name');

        $this->assertEquals('template-name', $post->template);
        $this->assertTrue($post->isTemplate('template-name'));
        $this->assertFalse($post->isTemplate('wrong-template-name'));
    }

    /**
     * @test
     */
    public function test_getting_the_post_template()
    {
        $model = $this->getModel();
        $post = $this->getPost($model);

        $model->shouldReceive('getComments')->twice()->with(['post_id' => 1])->andReturn('post-comments');

        $this->assertEquals('post-comments', $post->comments);
        // The `post_id` cannot be overwrite by arguments.
        $this->assertEquals('post-comments', $post->comments(['post_id' => 12]));
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

    public function getPost($model)
    {
        $config = new ApplicationConfig([
            'images' => ['size' => 'thumbnail'],
        ]);

        $post = new Post($config);

        $post
            ->setAdaptee(new WP_Post)
            ->setModel($model);

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
