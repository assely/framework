<?php

use Assely\Adapter\Comment;
use Assely\Config\ApplicationConfig;
use Brain\Monkey\Functions;
use Illuminate\Support\Collection;

class CommentTest extends TestCase
{
    /**
     * @test
     */
    public function test_comment_adapter_touched_properties()
    {
        $model = $this->getModel();
        $comment = $this->getComment($model);

        $timestamp = strtotime('1997-07-16 19:20:00');

        Functions::expect('get_option')->with('date_format')->andReturn('dd.mm.yyyy');
        Functions::expect('date_i18n')->with('dd.mm.yyyy', $timestamp)->andReturn('16.07.1997');

        $this->assertEquals('Agent', $comment->agent);
        $this->assertEquals('1', $comment->approved);
        $this->assertEquals('Author', $comment->author);
        $this->assertEquals('example@email.com', $comment->author_email);
        $this->assertEquals('127.0.0.1', $comment->author_ip);
        $this->assertEquals('website.com', $comment->author_url);
        $this->assertEquals('Content', $comment->content);
        $this->assertEquals('16.07.1997', $comment->created_at);
        $this->assertEquals(1, $comment->id);
        $this->assertEquals(3, $comment->karma);
        $this->assertEquals(4, $comment->parent_id);
        $this->assertEquals(2, $comment->post_id);
        $this->assertEquals('Type', $comment->type);
        $this->assertEquals(5, $comment->user_id);
    }

    /**
     * @test
     */
    public function test_checking_and_getting_comment_replies()
    {
        $model = $this->getModel();
        $comment = $this->getComment($model);

        $model->shouldReceive('plugAdapter')->with(Comment::class, [new WP_Comment])->andReturn([$comment]);

        $this->assertTrue($comment->hasReplies);
        $this->assertCount(1, $comment->replies);
        $this->assertContainsOnlyInstancesOf(Comment::class, $comment->replies);
    }

    /**
     * @test
     */
    public function test_converting_comment_adapter_instance_to_json_and_array()
    {
        $model = $this->getModel();
        $comment = $this->getComment($model);

        $timestamp = strtotime('1997-07-16 19:20:00');
        Functions::expect('get_option')->with('date_format')->andReturn('dd.mm.yyyy');
        Functions::expect('date_i18n')->with('dd.mm.yyyy', $timestamp)->andReturn('16.07.1997');

        $model->shouldReceive('plugAdapter')->with(Comment::class, [new WP_Comment])->andReturn(['comment']);

        $this->assertEquals('{"agent":"Agent","approved":"1","author":"Author","author_email":"example@email.com","author_ip":"127.0.0.1","author_url":"website.com","content":"Content","created_at":"16.07.1997","id":1,"karma":3,"parent_id":4,"post_id":2,"replies":["comment"],"type":"Type","user_id":5}', $comment->toJson());

        $this->assertEquals(["agent"=>"Agent","approved"=>"1","author"=>"Author","author_email"=>"example@email.com","author_ip"=>"127.0.0.1","author_url"=>"website.com","content"=>"Content","created_at"=>"16.07.1997","id"=>1,"karma"=>3,"parent_id"=>4,"post_id"=>2,"replies"=>['comment'],"type"=>"Type","user_id"=>5], $comment->toArray());
    }

    public function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\CommentModel');
    }

    public function getComment($model)
    {
        $config = new ApplicationConfig;

        $comment = new Comment($config);

        $comment
            ->setAdaptee(new WP_Comment)
            ->setModel($model);

        return $comment;
    }
}

class WP_Comment
{
    public $comment_ID = 1;
    public $comment_post_ID = 2;
    public $comment_author = 'Author';
    public $comment_author_email = 'example@email.com';
    public $comment_author_url = 'website.com';
    public $comment_author_IP = '127.0.0.1';
    public $comment_date = '1997-07-16 19:20:00';
    public $comment_date_gmt = '1997-07-16 19:20:00';
    public $comment_content = 'Content';
    public $comment_karma = 3;
    public $comment_approved = '1';
    public $comment_agent = 'Agent';
    public $comment_type = 'Type';
    public $comment_parent = 4;
    public $user_id = 5;

    public function get_children()
    {
        return [new self];
    }
}
