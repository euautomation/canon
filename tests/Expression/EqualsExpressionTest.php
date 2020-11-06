<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class EqualsExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueConstant()
    {
        $item = [
            'test' => 'testValue'
        ];

        $val = new ConstantValue('testValue');
        $val2 = new ConstantValue('testValue');
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueConstantNumbers()
    {
        $item = [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(1);
        $val2 = new ConstantValue(1);
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseConstant()
    {
        $item = [
            'test' => 'testValue'
        ];

        $val = new ConstantValue('test');
        $val2 = new ConstantValue('testt');
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueColumn()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $expr = new EqualsExpression($val, $val);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseColumn()
    {
        $item =  [
            'test' => 'testValue',
            'testt' => 'testtValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ColumnValue('testt');
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueColumnConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue('testvalue');
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseColumnConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue('test');
        $expr = new EqualsExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
