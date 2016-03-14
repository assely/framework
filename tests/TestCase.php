<?php

use Brain\Monkey;

class TestCase extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        Monkey::setUpWP();
    }

    protected function tearDown()
    {
        Monkey::tearDownWP();
        Mockery::close();
        parent::tearDown();
    }
}
