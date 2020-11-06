<?php

namespace EUAutomation\Canon\Expression;

class NotExpression extends Expression
{
    /**
     * @var Expression
     */
    protected $child;

    /**
     * NotExpression constructor.
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        $this->child = $expression;
    }

    /**
     * @return Expression
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Expression $child
     */
    public function setChild(Expression $child)
    {
        $this->child = $child;
    }

    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return !$this->child->evaluate($item);
    }
}
