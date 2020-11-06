<?php

namespace EUAutomation\Canon\Value\Functions;

use EUAutomation\Canon\Value\FunctionValue;

class LengthFunction extends FunctionValue
{
    /**
     * @var int
     */
    protected $requiredArguments = 1;

    /**
     * @param array $args
     * @return int
     */
    protected function execute($args)
    {
        return strlen($args[0]);
    }
}
