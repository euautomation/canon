<?php

namespace EUAutomation\Canon\Value;

use EUAutomation\Canon\Processors\SyntaxErrorException;

abstract class FunctionValue implements Value
{
    /**
     * @var int
     */
    protected $requiredArguments = 0;

    /**
     * @var Value[]
     */
    protected $args;

    /**
     * FunctionValue constructor.
     * @param Value[] $args
     */
    public function __construct($args = [])
    {
        $this->args = $args;
        $this->validateArguments();
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function validateArguments()
    {
        if (count($this->args) < $this->requiredArguments) {
            throw new SyntaxErrorException();
        }
    }

    /**
     * @param array $args
     * @return mixed
     */
    abstract protected function execute($args);

    /**
     * @param $item
     * @return mixed
     */
    public function value($item)
    {
        return $this->execute(array_map(function (Value $value) use ($item) {
            return $value->value($item);
        }, $this->args));
    }
}
