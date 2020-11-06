<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\Value;

class InExpression extends UnaryExpression
{
    /**
     * @var array
     */
    protected $list;

    /**
     * InExpression constructor.
     * @param Value $left
     * @param array $list
     */
    public function __construct(Value $left, array $list)
    {
        parent::__construct($left);
        $this->list = $list;
    }

    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return in_array($this->left->value($item), $this->list);
    }
}
