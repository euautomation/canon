<?php

namespace EUAutomation\Canon\Expression;

use App\Models\FeedItem;

class NotExpressionTest extends \PHPUnit\Framework\TestCase
{
    protected function makeTrue($boolean = 'and')
    {
        $mock = \Mockery::mock(Expression::class);
        $mock->shouldReceive('evaluate')->andReturn(true);
        return $mock;
    }

    protected function makeFalse($boolean = 'and')
    {
        $mock = \Mockery::mock(Expression::class);
        $mock->shouldReceive('evaluate')->andReturn(false);
        return $mock;
    }

    public function testNotExpression()
    {
        $item = [];

        $notExpr = new NotExpression($this->makeTrue());
        $result = $notExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testNotNotExpression()
    {
        $item = [];

        $notExpr = new NotExpression($this->makeFalse());
        $result = $notExpr->evaluate($item);
        $this->assertTrue($result);
    }
}
