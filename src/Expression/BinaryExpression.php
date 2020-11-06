<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\Value;

abstract class BinaryExpression extends Expression
{
    /**
     * @var Value
     */
    protected $left;
    /**
     * @var Value
     */
    protected $right;

    /**
     * EqualsOperator constructor.
     * @param Value $left
     * @param Value $right
     */
    public function __construct(Value $left, Value $right)
    {
        $this->left = $left;
        $this->right = $right;
    }
}
