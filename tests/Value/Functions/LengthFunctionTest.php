<?php

namespace EUAutomation\Canon\Value\Functions;


use EUAutomation\Canon\Processors\SyntaxErrorException;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class LengthFunctionTest extends \PHPUnit\Framework\TestCase
{

    public function testWithNotEnoughArguments() {
        $this->expectException(SyntaxErrorException::class);
        new LengthFunction([]);
    }

    public function testReturnsFooAsLength3() {
        $function = new LengthFunction([new ConstantValue('foo')]);
        $this->assertEquals(3, $function->value($this->makeFeedItem()));
    }

    public function testReturnsFooAsLength6() {
        $function = new LengthFunction([new ConstantValue('foobar')]);
        $this->assertEquals(6, $function->value($this->makeFeedItem()));
    }

    public function testReturnsNullAsLength0() {
        $function = new LengthFunction([new ConstantValue(null)]);
        $this->assertEquals(0, $function->value($this->makeFeedItem()));
    }

    private function makeFeedItem($attributes = []) {
        return $attributes;
    }
}
