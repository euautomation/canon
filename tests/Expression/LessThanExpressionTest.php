<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class LessThanExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueLessThanNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(1);
        $val2 = new ConstantValue(2);
        $expr = new LessThanExpression($val, $val2);

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
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLessThanNumericConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue(3);
        $val2 = new ConstantValue(2);
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateFalseLessThanStringConstant()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $val = new ConstantValue("abc");
        $val2 = new ConstantValue("a");
        $expr = new LessThanExpression($val, $val2);

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
        $expr = new LessThanExpression($val, $val2);

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
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLessThanNumericColumn()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateFalseLessThanStringColumn()
    {
        $item =  [
            'test' => "abc",
            'test2' => "a",
        ];

        $val = new ColumnValue("test");
        $val2 = new ColumnValue("test2");
        $expr = new LessThanExpression($val, $val2);

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
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateTrueLessThanStringColumnConstant()
    {
        $item =  [
            'test' => "a",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("abc");
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLessThanNumericColumnConstant()
    {
        $item =  [
            'test' => 3,
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue(2);
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateFalseLessThanStringColumnConstant()
    {
        $item =  [
            'test' => "abc",
            'test2' => 2,
        ];

        $val = new ColumnValue("test");
        $val2 = new ConstantValue("a");
        $expr = new LessThanExpression($val, $val2);

        $result = $expr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
