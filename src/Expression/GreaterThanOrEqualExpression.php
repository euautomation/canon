<?php

namespace EUAutomation\Canon\Expression;

class GreaterThanOrEqualExpression extends BinaryExpression
{
    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return ($this->left->value($item) >= $this->right->value($item));
    }
}
