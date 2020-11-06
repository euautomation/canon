<?php

namespace EUAutomation\Canon\Value\Functions;


use EUAutomation\Canon\Processors\SyntaxErrorException;
use EUAutomation\Canon\Value\ConstantValue as CV;
use App\Models\FeedItem;

class SubstrFunctionTest extends \PHPUnit\Framework\TestCase
{
    public function testWithNoEnoughArguments() {
        $this->expectException(SyntaxErrorException::class);
        new SubstrFunction([]);
    }

    public function testWith1EnoughArguments() {
        $this->expectException(SyntaxErrorException::class);
        new SubstrFunction([new CV('foo')]);
    }

    public function testWithArgument1NonNumeric() {
        $this->assertFalse((new SubstrFunction([new CV('foo'), new CV('foo')]))->value($this->makeFeedItem()));
        $this->assertFalse((new SubstrFunction([new CV('foo'), new CV('foo'), new CV(1)]))->value($this->makeFeedItem()));
    }

    public function testWithArgument2NonNumeric() {
        $this->assertFalse((new SubstrFunction([new CV('foo'), new CV(1), new CV('foo')]))->value($this->makeFeedItem()));
    }

    public function testWithFoo1And1() {
        $this->assertEquals('o', (new SubstrFunction([new CV('foo'), new CV(1), new CV(1)]))->value($this->makeFeedItem()));
    }

    public function testWithFooMinus3And1() {
        $this->assertEquals('f', (new SubstrFunction([new CV('foo'), new CV(-3), new CV(1)]))->value($this->makeFeedItem()));
    }

    public function testWithFoo1AndMinus1() {
        $this->assertEquals('fo', (new SubstrFunction([new CV('foo'), new CV(0), new CV(-1)]))->value($this->makeFeedItem()));
    }

    private function makeFeedItem($attributes = []) {
        return $attributes;
    }
}
