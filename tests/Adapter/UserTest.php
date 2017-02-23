<?php

use Assely\Adapter\User;
use Brain\Monkey\Functions;
use Illuminate\Support\Collection;
use Assely\Config\ApplicationConfig;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function test_user_adapter_touched_properties()
    {
        $model = $this->getModel();
        $user = $this->getUser($model);

        $timestamp = strtotime('1997-07-16 19:20:00');

        Functions::expect('get_option')->with('date_format')->andReturn('dd.mm.yyyy');
        Functions::expect('date_i18n')->with('dd.mm.yyyy', $timestamp)->andReturn('16.07.1997');

        $this->assertEquals('activation_key', $user->activation_key);
        $this->assertEquals(['cap1', 'cap2'], $user->capabilities);
        $this->assertEquals('cap_key', $user->capability_key);
        $this->assertEquals('16.07.1997', $user->created_at);
        $this->assertEquals('example@email.com', $user->email);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('user', $user->login);
        $this->assertEquals('User', $user->name);
        $this->assertEquals('password', $user->password);
        $this->assertEquals(['cap1', 'cap2', 'cap3'], $user->premissions);
        $this->assertEquals(['role1', 'role2'], $user->roles);
        $this->assertEquals('status', $user->status);
        $this->assertEquals('username', $user->username);
        $this->assertEquals('http://website.com', $user->website);
    }

    /**
     * @test
     */
    public function test_user_caps_checker()
    {
        $model = $this->getModel();
        $user = $this->getUser($model);

        $this->assertTrue($user->can('cap1'));
        $this->assertFalse($user->can('missing-cap'));
    }

    /**
     * @test
     */
    public function test_user_role_checker()
    {
        $model = $this->getModel();
        $user = $this->getUser($model);

        $this->assertTrue($user->hasRole('role1'));
        $this->assertFalse($user->hasRole('missing-role'));
    }

    /**
     * @test
     */
    public function test_getting_the_user_metadata()
    {
        $model = $this->getModel();
        $user = $this->getUser($model);

        $model->shouldReceive('findMeta')->once()->with(1, 'key')->andReturn('key-metadata');
        $model->shouldReceive('getMeta')->once()->with(1)->andReturn('all-metadata');

        $this->assertEquals('key-metadata', $user->meta('key'));
        $this->assertEquals('all-metadata', $user->meta);
    }

    /**
     * @test
     */
    public function test_converting_user_adapter_instance_to_json()
    {
        $model = $this->getModel();
        $user = $this->getUser($model);

        $model->shouldReceive('getMeta')->once()->with(1)->andReturn(new Collection(['meta' => 'data']));

        $this->assertEquals('{"activation_key":"activation_key","capabilities":["cap1","cap2"],"capability_key":"cap_key","created_at":null,"email":"example@email.com","id":1,"login":"user","meta":{"meta":"data"},"name":"User","password":"password","premissions":["cap1","cap2","cap3"],"roles":["role1","role2"],"status":"status","username":"username","website":"http:\/\/website.com"}', $user->toJson());
    }

    public function getModel()
    {
        return Mockery::mock('Assely\Singularity\Model\UserModel');
    }

    public function getUser($model)
    {
        $config = new ApplicationConfig([
            'images' => ['size' => 'thumbnail'],
        ]);

        $user = new User($config);

        $user
            ->setAdaptee(new WP_User)
            ->setModel($model);

        return $user;
    }
}

class WP_User
{
    public $user_activation_key = 'activation_key';
    public $caps = ['cap1', 'cap2'];
    public $cap_key = 'cap_key';
    public $user_registered = '1997-07-16 19:20:00';
    public $user_email = 'example@email.com';
    public $ID = 1;
    public $user_login = 'user';
    public $display_name = 'User';
    public $user_pass = 'password';
    public $allcaps = ['cap1', 'cap2', 'cap3'];
    public $roles = ['role1', 'role2'];
    public $user_status = 'status';
    public $user_nicename = 'username';
    public $user_url = 'http://website.com';

    public function has_cap($cap)
    {
        return in_array($cap, $this->caps);
    }
}
