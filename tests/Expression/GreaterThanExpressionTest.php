<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class GreaterThanExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(1);
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("a");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abcd");
        $val2 = new ConstantValue("abcd");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 2
        ];

        $val = new ColumnValue('test');
        $val2 = new ColumnValue('test2');
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 3
        ];

        $val = new ColumnValue('test');
        $val2 = new ColumnValue('test2');
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueStringColumn()
    {
        $item =  [
            'test' => 'testValue',
            'test2' => 'testValu',
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseStringColumn()
    {
        $item =  [
            'test' => 'testValue',
            'test2' => 'testValue',
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 2
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 3
        ];

        $val = new ColumnValue('test');
        $val2 = new ConstantValue(3);
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueStringColumnConstant()
    {
        $item =  [
            'test' => 'testValue',
            'test2' => 'testValu',
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("test2");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseStringColumnConstant()
    {
        $item =  [
            'test' => 'testValue',
            'test2' => 'testValue',
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("testvalueee");
        $expr = new GreaterThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
