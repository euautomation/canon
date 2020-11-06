<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class LessThanOrEqualExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueLessThanNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(1);
        $val2 = new ConstantValue(2);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanOrEqualNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(3);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("a");
        $val2 = new ConstantValue("abc");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanOrEqualStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("abc");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLessThanOrEqualNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(2);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateFalseLessThanOrEqualStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("ab");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateTrueLessThanNumericColumn()
    {
        $item =  [
            'test' => 1,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanOrEqualNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 3,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanStringColumn()
    {
        $item =  [
            'test' => "a",
            'test2' => "abc",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);

    }

    public function testEvaluateTrueLessThanOrEqualStringColumn()
    {
        $item =  [
            'test' => "abc",
            'test2' => "abc",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);

    }

    public function testEvaluateFalseLessThanOrEqualNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);

    }

    public function testEvaluateFalseLessThanOrEqualStringColumn()
    {
        $item =  [
            'test' => "abc",
            'test2' => "a",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLessThanNumericColumnConstant()
    {
        $item =  [
            'test' => 1,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(2);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanOrEqualNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(3);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanStringColumnConstant()
    {
        $item =  [
            'test' => "ab",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abc");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanOrEqualStringColumnConstant()
    {
        $item =  [
            'test' => "abcd",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abcd");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLessThanOrEqualNumericColumnConstant()
    {
        $item =  [
            'test' => 4,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(3);
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateFalseLessThanOrEqualStringColumnConstant()
    {
        $item =  [
            'test' => "abcd",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abc");
        $expr = new LessThanOrEqualExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
