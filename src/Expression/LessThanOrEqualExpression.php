<?php

namespace EUAutomation\Canon\Expression;

class LessThanOrEqualExpression extends BinaryExpression
{
    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return ($this->left->value($item) <= $this->right->value($item));
    }
}
