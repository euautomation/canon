<?php

namespace EUAutomation\Canon\Value;

abstract class BaseValue implements Value
{
    /**
     * @var mixed|string
     */
    protected $base_expr;

    /**
     * BaseValue constructor.
     * @param $base_expr
     */
    public function __construct($base_expr)
    {
        $this->base_expr = $base_expr;
    }
}
