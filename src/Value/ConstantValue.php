<?php

namespace EUAutomation\Canon\Value;

class ConstantValue extends BaseValue
{
    /**
     * @param $item
     * @return mixed
     */
    public function value($item)
    {
        return $this->base_expr;
    }
}
