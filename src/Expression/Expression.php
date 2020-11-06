<?php

namespace EUAutomation\Canon\Expression;

abstract class Expression
{
    /**
     * @var string
     */
    protected $boolean = 'AND';

    /**
     * @param $item
     * @return boolean
     */
    abstract public function evaluate($item);

    /**
     * @return string
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * @param string $boolean
     */
    public function setBoolean($boolean)
    {
        $this->boolean = $boolean;
    }
}
