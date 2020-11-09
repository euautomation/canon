<?php

namespace EUAutomation\Canon\Processors;

use EUAutomation\Canon\Expression\AlternativeTruthExpression;
use EUAutomation\Canon\Expression\Collection;
use EUAutomation\Canon\Expression\EqualsExpression;
use EUAutomation\Canon\Expression\Expression;
use EUAutomation\Canon\Expression\GreaterThanExpression;
use EUAutomation\Canon\Expression\GreaterThanOrEqualExpression;
use EUAutomation\Canon\Expression\InExpression;
use EUAutomation\Canon\Expression\IsNullExpression;
use EUAutomation\Canon\Expression\LessThanExpression;
use EUAutomation\Canon\Expression\LessThanOrEqualExpression;
use EUAutomation\Canon\Expression\LikeAnyExpression;
use EUAutomation\Canon\Expression\LikeExpression;
use EUAutomation\Canon\Expression\NotEqualsExpression;
use EUAutomation\Canon\Expression\NotExpression;
use EUAutomation\Canon\Value\ColumnValue;
use EUAutomation\Canon\Value\ConstantValue;
use EUAutomation\Canon\Value\FunctionValue;
use EUAutomation\Canon\Value\Value;
use ReflectionClass;


class ExpressionProcessor
{

    /**
     * @var array
     */
    protected $binaryExpressions = [
        '=' => EqualsExpression::class,
        '!=' => NotEqualsExpression::class,
        '>' => GreaterThanExpression::class,
        '<' => LessThanExpression::class,
        '>=' => GreaterThanOrEqualExpression::class,
        '<=' => LessThanOrEqualExpression::class
    ];

    /**
     * @param $tokens
     * @return Collection
     * @throws SyntaxErrorException
     */
    public function process($tokens)
    {
        $groups = $this->buildGroups($tokens);

        return $groups;
    }

    private function canBeValue(ExpressionToken $token) {
        return $token->isConstant() || $token->isString() || $token->isNumber() || $token->isColumnReference() || $token->isFunction();
    }

    /**
     * @param ExpressionToken[] $tokens
     * @return Collection
     *
     * @throws SyntaxErrorException
     */
    protected function buildGroups($tokens)
    {
        $collection = new Collection();
        /**
         * @var Expression|null $prev
         */
        $prev = null;

        $count = count($tokens);
        for ($i = 0; $i < $count; $i++) {
            if (
                $i + 2 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                in_array($tokens[$i + 1]->getToken(), array_keys($this->binaryExpressions)) &&
                $this->canBeValue($tokens[$i + 2])
            ) { // is binary expression
                $collection->push($prev = $this->handleBinaryExpressions($tokens[$i + 1]->getToken(), $tokens[$i], $tokens[$i + 2]));
                $i += 2;
            } elseif ($tokens[$i]->isOperator() && in_array($tokens[$i]->getUpper(), ['AND', 'OR'])) {
                $prev->setBoolean($tokens[$i]->getUpper());
            } elseif ($tokens[$i]->isBracketExpression()) {
                $collection->push($prev = $this->buildGroups($tokens[$i]->getSubTree()));
            } elseif (
                $i + 2 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                $tokens[$i + 1]->getUpper() == 'LIKE' &&
                $tokens[$i + 2]->isString()
            ) {
                $collection->push($prev = $this->handleLike($tokens[$i], $tokens[$i + 2]));
                $i += 2;
            } elseif (
                $i + 2 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                $tokens[$i + 1]->getUpper() == 'IN' &&
                $tokens[$i + 2]->isInList()
            ) {
                $collection->push($prev = $this->handleIn($tokens[$i], $tokens[$i + 2]));
                $i += 2;
            } elseif (
                $i + 3 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                $tokens[$i + 1]->getUpper() == 'NOT' &&
                $tokens[$i + 2]->isOperator() &&
                $tokens[$i + 2]->getUpper() == 'LIKE' &&
                $tokens[$i + 3]->isString()
            ) {
                $collection->push($prev = $this->handleNot($this->handleLike($tokens[$i], $tokens[$i + 3])));
                $i += 3;
            } elseif (
                $i + 3 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                $tokens[$i + 1]->getUpper() == 'NOT' &&
                $tokens[$i + 2]->isOperator() &&
                $tokens[$i + 2]->getUpper() == 'IN' &&
                $tokens[$i + 3]->isInList()
            ) {
                $collection->push($prev = $this->handleNot($this->handleIn($tokens[$i], $tokens[$i + 3])));
                $i += 3;
            } elseif (
                $i + 3 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator() &&
                $tokens[$i + 1]->getUpper() == 'IS' &&
                $tokens[$i + 2]->isOperator() &&
                $tokens[$i + 2]->getUpper() == 'NOT' &&
                $tokens[$i + 3]->isConstant() &&
                $tokens[$i + 3]->getUpper() == 'NULL'
            ) {
                $collection->push($prev = $this->handleNot($this->handleIsNull($tokens[$i])));
                $i += 3;
            } elseif (
                $i + 2 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator()  &&
                $tokens[$i + 1]->getUpper() == 'IS' &&
                $tokens[$i + 2]->isConstant() &&
                $tokens[$i + 2]->getUpper() == 'NULL'
            ) {
                $collection->push($prev = $this->handleIsNull($tokens[$i]));
                $i += 2;
            } elseif (
                $i + 3 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator()  &&
                $tokens[$i + 1]->getUpper() == 'LIKE' &&
                $tokens[$i + 2]->isOperator() &&
                $tokens[$i + 2]->getUpper() == 'ANY' &&
                $tokens[$i + 3]->isArray()
            ) {
                $collection->push($prev = $this->handleLikeAny($tokens[$i], $tokens[$i + 3]));
                $i += 3;
            } elseif (
                $i + 4 < $count &&
                $this->canBeValue($tokens[$i]) &&
                $tokens[$i + 1]->isOperator()  &&
                $tokens[$i + 1]->getUpper() == 'NOT' &&
                $tokens[$i + 2]->isOperator()  &&
                $tokens[$i + 2]->getUpper() == 'LIKE' &&
                $tokens[$i + 3]->isOperator() &&
                $tokens[$i + 3]->getUpper() == 'ANY' &&
                $tokens[$i + 4]->isArray()
            ) {
                $collection->push($prev = $this->handleNot($this->handleLikeAny($tokens[$i], $tokens[$i + 4])));
                $i += 4;
            } else {
                throw new SyntaxErrorException();
            }
        }

        return $collection;
    }

    /**
     * @param $expression
     * @param ExpressionToken $left
     * @param ExpressionToken $right
     * @return object
     */
    protected function handleBinaryExpressions($expression, $left, $right)
    {
        $class = new ReflectionClass($this->binaryExpressions[$expression]);
        return $class->newInstance($this->handleValue($left), $this->handleValue($right));
    }

    /**
     * @param ExpressionToken $token
     * @return Value
     *
     * @throws SyntaxErrorException
     */
    protected function handleValue(ExpressionToken $token)
    {
        if($token->isColumnReference()) {
            return new ColumnValue($token->getNoQuotes()['parts']);
        } else if($token->isFunction()) {
            return $this->handleFunction($token);
        } else if($token->isNumber()) {
            return new ConstantValue($this->toNumber($token->getToken()));
        } else if($token->isString()) {
            return new ConstantValue($this->cleanString($token->getToken()));
        } else if($token->isConstant()) {
            return new ConstantValue($this->transformConstant($token->getToken()));
        }
        throw new SyntaxErrorException();
    }

    public function handleFixedValues(ExpressionToken $token) {
        if($token->isNumber()) {
            return new ConstantValue($this->toNumber($token->getToken()));
        } else if($token->isString()) {
            return new ConstantValue($this->cleanString($token->getToken()));
        } else if($token->isConstant()) {
            return new ConstantValue($this->transformConstant($token->getToken()));
        }
        throw new SyntaxErrorException();
    }

    /**
     * @param ExpressionToken $left
     * @param ExpressionToken $right
     * @return LikeExpression
     */
    protected function handleLike($left, $right)
    {
        return new LikeExpression($this->handleValue($left), $this->cleanString($right->getToken()));
    }

    /**
     * @param ExpressionToken $left
     * @param ExpressionToken $right
     * @return LikeAnyExpression
     */
    protected function handleLikeAny($left, $right)
    {
        return new LikeAnyExpression($this->handleValue($left), array_map(function ($token) {
            return $this->cleanString($token->getToken());
        }, $right->getSubTree()));
    }

    /**
     * @param Expression $expression
     * @return NotExpression
     */
    protected function handleNot(Expression $expression)
    {
        return new NotExpression($expression);
    }

    /**
     * @param ExpressionToken $left
     * @param ExpressionToken $right
     * @return InExpression
     */
    protected function handleIn(ExpressionToken $left, ExpressionToken $right)
    {
        return new InExpression($this->handleValue($left), array_map(function ($token) {
            return $this->handleFixedValues($token)->value([]);
        }, $right->getSubTree()));
    }

    /**
     * @param $left
     * @return IsNullExpression
     */
    protected function handleIsNull($left)
    {
        return new IsNullExpression($this->handleValue($left));
    }

    /**
     * @param ExpressionToken $token
     * @return FunctionValue
     * @throws SyntaxErrorException
     */
    protected function handleFunction($token)
    {
        $functionClass = Constants::getInstance()->getFunctionClass(strtoupper($token->getToken()));

        if ($functionClass) {
            $reflection = new ReflectionClass($functionClass);
            $args = array_map(function ($token) {
                return $this->handleValue($token);
            }, $token->getSubTree());
            return $reflection->newInstance($args);
        }
        throw new SyntaxErrorException();
    }

    /**
     * @param $string
     * @return string
     */
    protected function cleanString($string)
    {
        $string = mb_strtolower($string);
        $length = strlen($string);
        if ($length < 2) {
            return $string;
        }
        if ($string[0] == '"' && $string[$length - 1] == '"') {
            $string = substr($string, 1, -1);
        } elseif ($string[0] == "'" && $string[$length - 1] == "'") {
            $string = substr($string, 1, -1);
        }
        return $string;
    }

    private function toNumber($string) {
        return floatval($string);
    }

    protected function transformConstant($string)
    {
        if($string === "true") {
            return true;
        } else if($string === "false") {
            return false;
        } else if($string === "null") {
            return null;
        } else {
            return $string;
        }
    }
}
