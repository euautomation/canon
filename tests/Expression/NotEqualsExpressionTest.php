<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class NotEqualsExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateFalseConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue('test');
        $expr = new NotEqualsExpression($val, $val);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue('test');
        $val2 = new ConstantValue('testt');
        $expr = new NotEqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseColumn()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $expr = new NotEqualsExpression($val, $val);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueColumn()
    {
        $item =  [
            'test' => 'testValue',
            'testt' => 'testtValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ColumnValue('testt');
        $expr = new NotEqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseColumnConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue('testvalue');
        $expr = new NotEqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueColumnConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue('test');
        $expr = new NotEqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }
}
