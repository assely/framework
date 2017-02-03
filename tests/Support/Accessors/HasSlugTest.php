<?php

use Brain\Monkey\Functions;

class HasSlugTest extends TestCase
{
    /**
     * @test
     */
    public function slug_should_be_slugifed_and_accessable()
    {
        Functions::expect('sanitize_title')->once()->andReturn('dummy-slug');

        $stub = new HasSlugTraitStub;

        $this->assertInstanceOf('HasSlugTraitStub', $stub->setSlug('dummy-slug'));
        $this->assertEquals('dummy-slug', $stub->getSlug());
    }
}

class HasSlugTraitStub
{
    use Assely\Support\Accessors\HasSlug;
}
