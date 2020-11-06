<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use App\Models\FeedItem;

class LikeExpressionTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluateTrueLikeConstantForward()
    {
        $item = [];

        $val = new ConstantValue('Hello World');
        $likeExpr = new LikeExpression($val, 'H%e%l%l%o%');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeConstantForward()
    {
        $item = [];

        $val = new ConstantValue('Hwello World');
        $likeExpr = new LikeExpression($val, 'Hel%');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLikeConstantBackward()
    {
        $item = [];

        $val = new ConstantValue('Hello');
        $likeExpr = new LikeExpression($val, '%ello');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeConstantBackward()
    {
        $item = [];

        $val = new ConstantValue('Hwello');
        $likeExpr = new LikeExpression($val, '%wllo');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLikeConstantBoth()
    {
        $item = [];

        $val = new ConstantValue('Hello World');
        $likeExpr = new LikeExpression($val, '%ello%');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeConstantBoth()
    {
        $item = [];

        $val = new ConstantValue('Hwello World');
        $likeExpr = new LikeExpression($val, '%wllo%');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLikeColumnForward()
    {
        $item =  [
            'test' => 'Hello World'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, 'Hel%');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeColumnForward()
    {
        $item =  [
            'test' => 'Hwello World'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, 'Hel%');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLikeColumnBackward()
    {
        $item =  [
            'test' => 'Hello'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, '%ello');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeColumnBackward()
    {
        $item =  [
            'test' => 'Hwello World'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, '%wllo');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }

    public function testEvaluateTrueLikeColumnBoth()
    {
        $item =  [
            'test' => 'Hello World'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, '%ello%');
        $result = $likeExpr->evaluate($item);
        $this->assertTrue($result);
    }

    public function testEvaluateFalseLikeColumnBoth()
    {
        $item =  [
            'test' => 'Hwello World'
        ];

        $val = new ColumnValue('test');
        $likeExpr = new LikeExpression($val, '%wllo%');
        $result = $likeExpr->evaluate($item);
        $this->assertNotTrue($result);
    }
}
