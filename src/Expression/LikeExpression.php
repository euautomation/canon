<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\Value;

class LikeExpression extends LikeAnyExpression
{
    /**
     * @var string
     */
    protected $regex;

    /**
     * LikeExpression constructor.
     * @param Value $left
     * @param string $pattern
     */
    public function __construct(Value $left, string $pattern)
    {
        parent::__construct($left, [$pattern]);
    }
}
