<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class GreaterThanOrEqualExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueGreaterThanNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanOrEqualNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(3);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("a");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanOrEqualStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("abc");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseGreaterThanOrEqualNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(1);
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateFalseGreaterThanOrEqualStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("abcd");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateTrueGreaterThanNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanOrEqualNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 3,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanStringColumn()
    {
        $item =  [
            'test' => "abc",
            'test2' => "a",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);

    }

    public function testEvaluateTrueGreaterThanOrEqualStringColumn()
    {
        $item =  [
            'test' => "abc",
            'test2' => "abc",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);

    }

    public function testEvaluateFalseGreaterThanOrEqualNumericColumn()
    {
        $item =  [
            'test' => 1,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateFalseGreaterThanOrEqualStringColumn()
    {
        $item =  [
            'test' => "a",
            'test2' => "abc",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueGreaterThanNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(2);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanOrEqualNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(3);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanStringColumnConstant()
    {
        $item =  [
            'test' => "abcd",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abc");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueGreaterThanOrEqualStringColumnConstant()
    {
        $item =  [
            'test' => "abcd",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abcd");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseGreaterThanOrEqualNumericColumnConstant()
    {
        $item =  [
            'test' => 1,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(3);
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateFalseGreaterThanOrEqualStringColumnConstant()
    {
        $item =  [
            'test' => "abcd",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abcde");
        $expr = new GreaterThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
