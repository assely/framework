<?php

class HoldsColumnsTest extends TestCase
{
    /**
     * @test
     */
    public function getting_columns_should_return_columns_collection()
    {
        $stub = $this->getTraitStub();

        $this->assertInstanceOf('Assely\Column\ColumnsCollection', $stub->getColumns());
    }

    /**
     * @test
     */
    public function getting_column_by_slug_from_column_collection()
    {
        $stub = $this->getTraitStub();

        $this->assertInstanceOf('Assely\Column\ColumnMetabox', $stub->getColumn('metabox'));
    }

    protected function getTraitStub()
    {
        $columns = Mockery::mock('Assely\Column\ColumnsCollection');

        $columns->shouldReceive('setColumns')->andReturn([$this->getColumnMock()]);
        $columns->shouldReceive('getColumn')->andReturn($this->getColumnMock());

        return new HoldsColumnsTraitStub($columns);
    }

    /**
     * @return mixed
     */
    protected function getColumnMock()
    {
        $column = Mockery::mock('Assely\Column\ColumnMetabox');

        $column->shouldReceive('getSlug')->andReturn('metabox');

        return $column;
    }
}

class HoldsColumnsTraitStub
{
    use Assely\Support\Accessors\HoldsColumns;

    /**
     * @param $columns
     */
    public function __construct($columns)
    {
        $this->columns = $columns;
    }
}
