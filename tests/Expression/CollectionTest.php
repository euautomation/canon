<?php

namespace EUAutomation\Canon\Expression;


class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    public function setUp(): void
    {
        parent::setUp();
        $this->collection = new Collection();
    }

    public function testCount()
    {
        $this->collection->push($this->makeTrue());
        $this->assertEquals(1, $this->collection->count());
        $this->collection->push($this->makeTrue());
        $this->assertEquals(2, $this->collection->count());
    }

    public function testOffsetExists()
    {
        $this->collection->push($this->makeTrue());
        $this->assertTrue(isset($this->collection[0]));
        $this->assertFalse(isset($this->collection[1]));
    }

    public function testOffsetGet()
    {
        $this->collection->push($this->makeTrue());
        $this->assertEquals('AND', $this->collection[0]->getBoolean());
    }

    public function testOffsetSet()
    {
        $this->collection[0] = $this->makeTrue();
        $this->assertEquals('AND', $this->collection[0]->getBoolean());
    }

    public function testOffsetUnset()
    {
        $this->collection->push($this->makeTrue());
        unset($this->collection[0]);
        $this->assertNull($this->collection[0]);
    }

    public function testEmptyTrue()
    {
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrue()
    {
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalse()
    {
        $this->collection->push($this->makeFalse());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndFalse()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeFalse());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseAndTrue()
    {
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeTrue());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueOrFalse()
    {
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseOrTrue()
    {
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseOrFalse()
    {
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeFalse());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueOrTrue()
    {
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueOrTrueAndTrue()
    {
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueOrTrueAndFalse()
    {
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeFalse());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueOrFalseAndTrue()
    {
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseOrTrueAndTrue()
    {
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseOrFalseAndTrue()
    {
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeTrue());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseAndFalseOrTrue()
    {
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeTrue());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleFalseAndTrueOrTrue()
    {
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeTrue());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndFalseOrTrue()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndFalseOrTrueOrFalse()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndTrueOrFalseOrFalse()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeFalse());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndTrueOrTrueOrTrueAndFalse()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeFalse());
        $this->assertFalse($this->collection->evaluate($this->makeItem()));
    }

    public function testSimpleTrueAndTrueOrTrueOrTrueAndTrue()
    {
        $this->collection->push($this->makeTrue());
        $this->collection->push($this->makeTrue('OR'));
        $this->collection->push($this->makeFalse('OR'));
        $this->collection->push($this->makeFalse());
        $this->collection->push($this->makeTrue());
        $this->assertTrue($this->collection->evaluate($this->makeItem()));
    }

    private function makeItem($attributes = []) {
        return $attributes;
    }

    protected function makeTrue($boolean = 'AND'){
        $mock = \Mockery::mock(Expression::class);
        $mock->shouldReceive('evaluate')->andReturn(true);
        $mock->shouldReceive('getBoolean')->andReturn($boolean);
        return $mock;
    }

    protected function makeFalse($boolean = 'AND'){
        $mock = \Mockery::mock(Expression::class);
        $mock->shouldReceive('evaluate')->andReturn(false);
        $mock->shouldReceive('getBoolean')->andReturn($boolean);
        return $mock;
    }
}
