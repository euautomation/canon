<?php

namespace EUAutomation\Canon\Expression;

use EUAutomation\Canon\Value\Value;

class LikeExpression extends UnaryExpression
{

    /**
     * @var string
     */
    protected $regex;

    /**
     * LikeExpression constructor.
     * @param Value $left
     * @param $pattern
     */
    public function __construct($left, $pattern)
    {
        parent::__construct($left);
        $this->regex = $this->patternToRegex($pattern);
    }

    /**
     * @param $pattern
     * @return string
     */
    protected function patternToRegex($pattern)
    {
        return '/^' . preg_replace('/%/', '(.*)', $pattern) . '$/i';
    }

    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        return !!preg_match($this->regex, $this->left->value($item));
    }
}
