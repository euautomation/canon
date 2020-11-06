<?php

namespace EUAutomation\Canon\Value\Functions;


use EUAutomation\Canon\Processors\SyntaxErrorException;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;
use PHPUnit\Framework\TestCase;

class CleanFunctionTest extends TestCase
{
    public function testWithNotEnoughArguments() {
        $this->expectException(SyntaxErrorException::class);
        new CleanFunction([]);
    }

    public function testReturnsFooAsLength3() {
        $function = new CleanFunction([new ConstantValue('foo !\-\/\\\\{};:^&% \[\]_\"\'#=+<>,.~|\(\)')]);
        $this->assertEquals('foo', $function->value($this->makeFeedItem()));
    }

    private function makeFeedItem($attributes = []) {
        return $attributes;
    }
}
