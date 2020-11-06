<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\Value;

abstract class UnaryExpression extends Expression
{
    /**
     * @var Value
     */
    protected $left;

    /**
     * EqualsOperator constructor.
     * @param Value $left
     */
    public function __construct(Value $left)
    {
        $this->left = $left;
    }
}
