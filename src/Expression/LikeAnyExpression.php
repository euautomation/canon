<?php


namespace EUAutomation\Canon\Expression;


use EUAutomation\Canon\Value\Value;

class LikeAnyExpression extends UnaryExpression
{
    /**
     * @var string
     */
    protected $regex;

    /**
     * LikeExpression constructor.
     * @param Value $left
     * @param string[] $patterns
     */
    public function __construct(Value $left, array $patterns)
    {
        parent::__construct($left);
        $this->regex = $this->patternsToRegex($patterns);
    }

    /**
     * @param string[] $patterns
     * @return string
     */
    protected function patternsToRegex(array $patterns) : string
    {
        $regexParts = [];

        foreach ($patterns as $pattern) {
            $regexParts[] = preg_replace('/%/', '(.*)', $pattern);
        }

        return '/^' . implode("|", $regexParts) . '$/i';
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
