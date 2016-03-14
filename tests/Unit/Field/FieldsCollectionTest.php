<?php

use Assely\Field\FieldsCollection;

class FieldsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_accepts_schema_on_creation()
    {
        $fields = $this->getFieldsCollection();

        $this->assertEquals([
            $this->getFieldMock(),
            $this->getFieldMock(),
        ], $fields->getSchema());

        $this->assertCount(2, $fields->getSchema());
    }

    /**
     * @test
     */
    public function it_should_push_field_to_schema()
    {
        $fields = $this->getFieldsCollection();

        $fields->pushSchema($this->getFieldMock());

        $this->assertEquals([
            $this->getFieldMock(),
            $this->getFieldMock(),
            $this->getFieldMock(),
        ], $fields->getSchema());

        $this->assertCount(3, $fields->getSchema());
    }

    /**
     * @test
     */
    public function it_should_merge_fields_schema()
    {
        $fields = $this->getFieldsCollection();

        $fields->mergeSchema([$this->getFieldMock(), $this->getFieldMock()]);

        $this->assertEquals([
            $this->getFieldMock(),
            $this->getFieldMock(),
            $this->getFieldMock(),
            $this->getFieldMock(),
        ], $fields->getSchema());

        $this->assertCount(4, $fields->getSchema());
    }

    /**
     * @test
     */
    public function it_should_set_fields_schema()
    {
        $fields = $this->getFieldsCollection();

        $fields->setSchema([$this->getFieldMock()]);

        $this->assertEquals([
            $this->getFieldMock(),
        ], $fields->getSchema());

        $this->assertCount(1, $fields->getSchema());
    }

    /**
     * @test
     */
    public function it_propagate_fields_schema_with_values()
    {
        $fields = $this->getFieldsCollection();

        $fields->boostSchemaWithValues([
            'field1' => 'value',
            'field2' => 'value',
        ]);

        $this->assertCount(2, $fields->getFields());
    }

    /**
     * @test
     */
    public function it_should_set_fields_values_after_propagation()
    {
        $fields = $this->getFieldsCollection();

        $fields->boostSchemaWithValues([
            'field1' => 'value',
            'field2' => 'value',
        ]);

        $this->assertEquals([
            'field1' => 'value',
            'field2' => 'value',
        ], $fields->getValues());
    }

    /**
     * @test
     */
    public function it_should_return_all_propagated_fields_in_single_array()
    {
        $fields = $this->getFieldsCollection();

        $fields->boostSchemaWithValues([
            'field1' => 'value',
            'field2' => 'value',
        ]);

        $this->assertTrue(is_array($fields->getAll()));
        $this->assertCount(1, $fields->getAll());
    }

    protected function getFieldsCollection()
    {
        $finder = Mockery::mock('Assely\Field\FieldsFinder');

        $collection = new FieldsCollection($finder);

        return $collection->setSchema([
            $this->getFieldMock(),
            $this->getFieldMock(),
        ]);
    }

    public function getFieldMock()
    {
        $field = Mockery::mock('Assely\Field\Field');

        $field->shouldReceive('getSlug')->andReturn('field1', 'field2');
        $field->shouldReceive('getArgument')->withArgs(['default'])->andReturn('value');
        $field->shouldReceive('setValue')->withArgs(['value'])->andReturn($field);
        $field->shouldReceive('dispatch')->andReturn(null);

        return $field;
    }
}
