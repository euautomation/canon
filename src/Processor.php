<?php

namespace EUAutomation\Canon;

use EUAutomation\Canon\Expression\AlternativeTruthExpression;
use EUAutomation\Canon\Expression\Expression;
use EUAutomation\Canon\Processors\SyntaxErrorException;
use EUAutomation\Canon\Processors\TokenProcessor;
use EUAutomation\Canon\Processors\ExpressionProcessor;

class Processor
{

    /**
     * @param $sql
     * @return Expression
     * @throws SyntaxErrorException
     */
    public function process($sql) {
        return $this->getExpressions($this->getTokens($sql));
    }

    /**
     * @param $sql
     * @return Expression
     */
    public function processSafe($sql)
    {
        try {
            return $this->process($sql);
        } catch (SyntaxErrorException $exception) {
            return new AlternativeTruthExpression();
        }
    }

    /**
     * @param $sql
     * @return bool
     */
    public function valid($sql)
    {
        try {
            $this->getExpressions($this->getTokens($sql));
            return true;
        } catch (SyntaxErrorException $exception) {
            return false;
        }
    }

    /**
     * @param $sql
     * @return array
     */
    protected function getTokens($sql)
    {
        return (new TokenProcessor())->process($sql);
    }

    /**
     * @param $tokens
     * @return Expression
     * @throws SyntaxErrorException
     */
    protected function getExpressions($tokens)
    {
        return (new ExpressionProcessor())->process($tokens);
    }
}
