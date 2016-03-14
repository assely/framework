<?php

class HoldsFieldsTest extends TestCase
{
    /**
     * @test
     */
    public function getting_fields_should_return_columns_collection()
    {
        $stub = $this->getTraitStub();

        $this->assertInstanceOf('Assely\Field\FieldsCollection', $stub->getFields());
    }

    protected function getTraitStub()
    {
        $fields = Mockery::mock('Assely\Field\FieldsCollection');

        return new HoldsFieldsTraitStub($fields);
    }
}

class HoldsFieldsTraitStub
{
    use Assely\Support\Accessors\HoldsFields;

    /**
     * @param $fields
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
    }
}
