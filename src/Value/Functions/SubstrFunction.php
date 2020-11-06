<?php

namespace EUAutomation\Canon\Value\Functions;

use EUAutomation\Canon\Value\FunctionValue;

class SubstrFunction extends FunctionValue
{
    /**
     * @var int
     */
    protected $requiredArguments = 2;

    /**
     * @param array $args
     * @return int
     */
    protected function execute($args)
    {
        if (!is_numeric($args[1])) {
            return false;
        }
        if (count($args) >= 3) {
            if (!is_numeric($args[2])) {
                return false;
            }
            return mb_substr($args[0], $args[1], $args[2]);
        } else {
            return mb_substr($args[0], $args[1]);
        }
    }
}
