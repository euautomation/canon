<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class InExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueInConstant()
    {
        $item = [];

        $val = new ConstantValue(2);
        $list = [
            'test' => 2
        ];

        $inExpr = new InExpression($val, $list);
        $result = $inExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseInConstant()
    {
        $item = [];

        $val = new ConstantValue(2);
        $list = [
            'test' => 3
        ];

        $inExpr = new InExpression($val, $list);
        $result = $inExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueInColumn()
    {
        $item =  [
            'test' => 2,
        ];

        $val = new ColumnValue("test");
        $list = [
            'test' => 2
        ];

        $inExpr = new InExpression($val, $list);
        $result = $inExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseInColumn()
    {
        $item =  [
            'test' => 4,
        ];

        $val = new ColumnValue("test");
        $list = [
            'test' => 3
        ];

        $inExpr = new InExpression($val, $list);
        $result = $inExpr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
