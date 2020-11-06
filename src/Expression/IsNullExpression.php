<?php


namespace EUAutomation\Canon\Expression;


class IsNullExpression extends UnaryExpression
{
    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return is_null($this->left->value($item));
    }
}
