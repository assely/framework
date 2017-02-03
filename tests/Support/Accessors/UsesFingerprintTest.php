<?php

class UsesFingerprintTest extends TestCase
{
    /**
     * @test
     */
    public function fingerprint_can_be_set_by_passing()
    {
        $stub = new UsesFingerprintTraitStub;

        $this->assertInstanceOf('UsesFingerprintTraitStub', $stub->setFingerprint('dummy-fingerprint'));
        $this->assertEquals('dummy-fingerprint', $stub->getFingerprint());
    }

    /**
     * @test
     */
    public function fingerprint_should_be_generated_if_is_not_passed()
    {
        $stub = new UsesFingerprintTraitStub;

        $this->assertInstanceOf('UsesFingerprintTraitStub', $stub->setFingerprint());
        $this->assertRegExp('/assely-[a-zA-Z0-9]+/', $stub->getFingerprint());
    }
}

class UsesFingerprintTraitStub
{
    use Assely\Support\Accessors\UsesFingerprint;
}
