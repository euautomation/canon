<?php

namespace EUAutomation\Canon;

use EUAutomation\Canon\Expression\AlternativeTruthExpression;
use EUAutomation\Canon\Expression\Collection;
use EUAutomation\Canon\Expression\EqualsExpression;
use EUAutomation\Canon\Expression\Expression;
use EUAutomation\Canon\Expression\GreaterThanExpression;
use EUAutomation\Canon\Expression\GreaterThanOrEqualExpression;
use EUAutomation\Canon\Expression\InExpression;
use EUAutomation\Canon\Expression\LessThanExpression;
use EUAutomation\Canon\Expression\LessThanOrEqualExpression;
use EUAutomation\Canon\Expression\LikeExpression;
use EUAutomation\Canon\Expression\NotEqualsExpression;
use EUAutomation\Canon\Expression\NotExpression;

class ProcessorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Processor
     */
    protected $processor;

    public function setUp(): void
    {
        parent::setUp();
        $this->processor = new Processor();
    }

    public function testProcessReturnsAExpression()
    {
        $expressions = $this->processor->process('foo = "bar"');
        $this->assertInstanceOf(Expression::class, $expressions);
    }

    public function testBasicEqualExpression()
    {
        $expressions = $this->processor->process('foo = "bar"');
        $this->assertEquals(1, $expressions->count());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testBasicNotEqualExpression()
    {
        $expressions = $this->processor->process('foo != "bar"');
        $this->assertInstanceOf(NotEqualsExpression::class, $expressions[0]);
        $this->assertFalse($expressions[0]->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testBasicGreaterThanExpression()
    {
        $expressions = $this->processor->process('foo > "bar"');
        $this->assertInstanceOf(GreaterThanExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bas'])));
    }

    public function testBasicGreaterThanOrEqualExpression()
    {
        $expressions = $this->processor->process('foo >= "bar"');
        $this->assertInstanceOf(GreaterThanOrEqualExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bas'])));
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testBasicLessThanExpression()
    {
        $expressions = $this->processor->process('foo < "bar"');
        $this->assertInstanceOf(LessThanExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'baq'])));
    }

    public function testBasicLessThanOrEqualExpression()
    {
        $expressions = $this->processor->process('foo <= "bar"');
        $this->assertInstanceOf(LessThanOrEqualExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'baq'])));
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testBasicLikeExpressionWithoutPattern()
    {
        $expressions = $this->processor->process('foo LIKE "bar"');
        $this->assertInstanceOf(LikeExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testBasicLikeExpression()
    {
        $expressions = $this->processor->process('foo LIKE "bar%"');
        $this->assertInstanceOf(LikeExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bart'])));
    }

    public function testBasicNotLikeExpression()
    {
        $expressions = $this->processor->process('foo NOT LIKE "bar%"');
        $this->assertInstanceOf(NotExpression::class, $expressions[0]);
        $this->assertInstanceOf(LikeExpression::class, $expressions[0]->getChild());
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 'bat'])));
    }

    public function testBasicInList()
    {
        $expressions = $this->processor->process('foo IN (1, 2, "3")');
        $this->assertInstanceOf(InExpression::class, $expressions[0]);
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => '1'])));
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 2])));
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 3])));
        $this->assertFalse($expressions[0]->evaluate($this->makeItem(['foo' => 4])));
    }

    public function testBasicNotInList()
    {
        $expressions = $this->processor->process('foo NOT IN (1, 2, "3")');
        $this->assertInstanceOf(NotExpression::class, $expressions[0]);
        $this->assertInstanceOf(InExpression::class, $expressions[0]->getChild());
        $this->assertTrue($expressions[0]->evaluate($this->makeItem(['foo' => 4])));
        $this->assertFalse($expressions[0]->evaluate($this->makeItem(['foo' => 3])));

    }

    public function testBasicEqualExpressionAnd()
    {
        $expressions = $this->processor->process('foo = "bar" and bar = "foo"');
        $this->assertEquals(2, $expressions->count());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertEquals('AND', $expressions[0]->getBoolean());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[1]);
    }

    public function testBasicEqualExpressionOr()
    {
        $expressions = $this->processor->process('foo = "bar" or bar = "foo"');
        $this->assertEquals(2, $expressions->count());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertEquals('OR', $expressions[0]->getBoolean());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[1]);
    }

    public function testBasicEqualExpressionInBrackets()
    {
        $expressions = $this->processor->process('(foo = "bar")');
        $this->assertEquals(1, $expressions->count());
        $this->assertInstanceOf(Collection::class, $expressions[0]);
        $this->assertEquals(1, $expressions[0]->count());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0][0]);
    }

    public function testBasicEqualExpressionAndInBrackets()
    {
        $expressions = $this->processor->process('(foo = "bar" and bar != "foo") or baz = "foo"');
        $this->assertEquals(2, $expressions->count());
        $this->assertInstanceOf(Collection::class, $expressions[0]);
        $this->assertEquals(2, $expressions[0]->count());
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0][0]);
        $this->assertInstanceOf(NotEqualsExpression::class, $expressions[0][1]);
        $this->assertEquals('AND', $expressions[0][0]->getBoolean());
        $this->assertEquals('OR', $expressions[0]->getBoolean());
    }


    public function testFullSimpleTrue()
    {
        $expressions = $this->processor->process('foo = "bar"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'bar'])));
    }

    public function testFullSimpleFalse()
    {
        $expressions = $this->processor->process('foo = "bar"');
        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => 'baz'])));
    }

    public function testFullComplexTrue()
    {
        $expressions = $this->processor->process('(foo = "bar" and bar != "foo") or baz = "foo" and bar = \'baz\'');
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'foo'
        ])));
    }

    public function testFullComplexFalse()
    {
        $expressions = $this->processor->process('(foo = "bar" and bar != "foo") or baz = "foo" and bar = \'baz\'');
        $this->assertFalse($expressions->evaluate($this->makeItem([
            'foo' => 'baz',
            'bar' => 'baz',
            'baz' => 'foz'
        ])));
    }

    public function testFunctionCallInQuery()
    {
        $expressions = $this->processor->process('length(foo) = 3');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => 'baz',
        ])));
        $this->assertFalse($expressions->evaluate($this->makeItem([
            'foo' => 'ba',
        ])));
        $this->assertFalse($expressions->evaluate($this->makeItem([
            'foo' => 'barr',
        ])));
    }

    public function testFunctionLength()
    {
        $expressions = $this->processor->process('length(foo) = 3');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
    }

    public function testFunctionSubstr()
    {
        $expressions = $this->processor->process('substr(foo, 2, 1) = "z"');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => 'baz',
        ])));
    }

    public function testFunctionStrip()
    {
        $expressions = $this->processor->process('strip(foo) = "z"');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => '^@z',
        ])));
    }

    public function testFunctionClean()
    {
        $expressions = $this->processor->process('clean(foo) = "z"');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => '^@z',
        ])));
    }

    public function testNestedFunction()
    {
        $expressions = $this->processor->process('substr(clean(foo), 0, 4) = "duck"');
        $this->assertInstanceOf(EqualsExpression::class, $expressions[0]);
        $this->assertTrue($expressions->evaluate($this->makeItem([
            'foo' => 'd!u{c]k^@z',
        ])));
    }

    public function testUnknownFunctionInQueryTrue()
    {
        $this->assertTrue($this->processor->valid('LENGTH(foo) = 3 OR (foo = "bar" and bar != "foo")'));
    }

    public function testUnknownFunctionInQueryFalse()
    {
        $this->assertFalse($this->processor->valid('ZZZZ(foo) = 3'));
    }

    public function testProcessSafeStillReturnsAnExpressionEvenIfItIsInvalid()
    {
        $expressions = $this->processor->processSafe('ZZZZ(foo) = 3');
        $this->assertInstanceOf(Expression::class, $expressions);
        $this->assertInstanceOf(AlternativeTruthExpression::class, $expressions);
        $this->assertFalse($expressions->evaluate($this->makeItem()));
    }

    public function testNestedAttributes(){
        $expressions = $this->processor->process('foo.bar = "baz"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => [ 'bar' => 'baz' ]])));
        $expressions = $this->processor->process('`foo`.`bar` = "baz"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => [ 'bar' => 'baz' ]])));
    }

    public function testBooleansTrue(){
        $expressions = $this->processor->process('foo = true');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => true ])));
    }

    public function testBooleansFalse(){
        $expressions = $this->processor->process('foo = false');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => false ])));
    }

    public function testIsNull(){
        $expressions = $this->processor->process('foo is null');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => null ])));
    }

    public function testIsNotNull(){
        $expressions = $this->processor->process('foo is not null');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'something' ])));
    }

    public function testCaseInsensitiveOperation()
    {
        $expressions = $this->processor->process('foo = "bar"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'BAR' ])));
        $expressions = $this->processor->process('foo = "BAR"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'bar' ])));

        $expressions = $this->processor->process('foo LIKE "bar"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'BAR' ])));
        $expressions = $this->processor->process('foo like "BAR"');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 'bar' ])));
    }

    public function testNumbers()
    {
        $expressions = $this->processor->process('foo = 2 AND (bar = 3 OR baz > 10)');
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 2, 'bar' => 3 ])));
        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => 2, 'bar' => 20 ])));

        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => 2, 'bar' => 2, 'baz' => 11 ])));
        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => 2, 'bar' => 2, 'baz' => 10 ])));
    }

    public function testLikeAny() {
        $expressions = $this->processor->process('foo LIKE ANY ("bar%", "baz%")');

        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => "bart" ])));
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => "bazt" ])));
        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => "bat" ])));
    }

    public function testNotLikeAny() {
        $expressions = $this->processor->process('foo NOT LIKE ANY ("bar%", "baz%")');

        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => "bart" ])));
        $this->assertFalse($expressions->evaluate($this->makeItem(['foo' => "bazt" ])));
        $this->assertTrue($expressions->evaluate($this->makeItem(['foo' => "bat" ])));
    }

    private function makeItem($attributes = []) {
        return $attributes;
    }
}
