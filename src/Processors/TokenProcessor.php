<?php

namespace EUAutomation\Canon\Processors;

use EUAutomation\Canon\Lexer\Lexer;

/**
 * Class TokenProcessor
 * @package EUAutomation\Canon\Processors
 * @see https://github.com/greenlion/PHP-SQL-Parser/blob/master/src/PHPSQLParser/processors/ExpressionListProcessor.php
 */
class TokenProcessor
{
    /**
     * @param $sql
     * @return array
     */
    protected function splitSQLIntoTokens($sql)
    {
        $lexer = new Lexer();
        return $lexer->split($sql);
    }

    /**
     * @param $sql
     * @return array
     */
    public function process($sql)
    {
        return $this->processTokens($this->splitSQLIntoTokens($sql));
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function processTokens($tokens)
    {
        $resultList = array();
        $skip_next = false;
        $prev = new ExpressionToken();

        foreach ($tokens as $tk => $tv) {
            if ($skip_next) {
                // skip the next non-whitespace token
                $skip_next = false;
                continue;
            }

            $curr = new ExpressionToken($tk, $tv);

            if ($curr->isWhitespaceToken()) {
                continue;
            }

            if ($curr->isEnclosedWithinParenthesis()) {
                /* is it an in-list? */
                $localTokenList = $this->splitSQLIntoTokens($this->removeParenthesisFromStart($curr->getTrim()));
                if (in_array($prev->getUpper(), [ 'ANY', 'IN' ])) {
                    foreach ($localTokenList as $k => $v) {
                        $tmpToken = new ExpressionToken($k, $v);
                        if ($tmpToken->isCommaToken()) {
                            unset($localTokenList[$k]);
                        }
                    }
                    $localTokenList = array_values($localTokenList);
                    $curr->setSubTree($this->processTokens($localTokenList));
                    if($prev->getUpper() === 'IN') {
                        $curr->setTokenType(ExpressionType::IN_LIST);
                    } else {
                        $curr->setTokenType(ExpressionType::ARRAY);
                    }
                } elseif ($prev->isColumnReference() || $prev->isFunction() || $prev->isAggregateFunction()
                    || $prev->isCustomFunction()
                ) {
                    // if we have a colref followed by a parenthesis pair,
                    // it isn't a colref, it is a user-function
                    // TODO: this should be a method, because we need the same code
                    // below for unspecified tokens (expressions).
                    $localExpr = new ExpressionToken();
                    $tmpExprList = array();
                    foreach ($localTokenList as $k => $v) {
                        $tmpToken = new ExpressionToken($k, $v);
                        if (!$tmpToken->isCommaToken()) {
                            $localExpr->addToken($v);
                            $tmpExprList[] = $v;
                        } else {
                            // an expression could have multiple parts split by operands
                            // if we have a comma, it is a split-point for expressions
                            $tmpExprList = array_values($tmpExprList);
                            $localExprList = $this->processTokens($tmpExprList);
                            if (count($localExprList) > 1) {
                                $localExpr->setSubTree($localExprList);
                                $localExpr->setTokenType(ExpressionType::EXPRESSION);
                                $localExprList = $localExpr->toArray();
                                $localExprList['alias'] = false;
                                $localExprList = array($localExprList);
                            }
                            if (!$curr->getSubTree()) {
                                if (!empty($localExprList)) {
                                    $curr->setSubTree($localExprList);
                                }
                            } else {
                                $tmpExprList = $curr->getSubTree();
                                $curr->setSubTree(array_merge($tmpExprList, $localExprList));
                            }
                            $tmpExprList = array();
                            $localExpr = new ExpressionToken();
                        }
                    }
                    $tmpExprList = array_values($tmpExprList);
                    $localExprList = $this->processTokens($tmpExprList);
                    if (count($localExprList) > 1) {
                        $localExpr->setSubTree($localExprList);
                        $localExpr->setTokenType(ExpressionType::EXPRESSION);
                        $localExprList = $localExpr->toArray();
                        $localExprList['alias'] = false;
                        $localExprList = array($localExprList);
                    }
                    if (!$curr->getSubTree()) {
                        if (!empty($localExprList)) {
                            $curr->setSubTree($localExprList);
                        }
                    } else {
                        $tmpExprList = $curr->getSubTree();
                        $curr->setSubTree(array_merge($tmpExprList, $localExprList));
                    }
                    $prev->setSubTree($curr->getSubTree());
                    if ($prev->isColumnReference()) {
                        if (Constants::getInstance()->isCustomFunction($prev->getUpper())) {
                            $prev->setTokenType(ExpressionType::CUSTOM_FUNCTION);
                        } else {
                            $prev->setTokenType(ExpressionType::SIMPLE_FUNCTION);
                        }
                        $prev->setNoQuotes(null);
                    }
                    array_pop($resultList);
                    $curr = $prev;
                }
                // we have parenthesis, but it seems to be an expression
                if ($curr->isUnspecified()) {
                    $localExpr = new ExpressionToken();
                    $tmpExprList = array();
                    foreach ($localTokenList as $k => $v) {
                        $tmpToken = new ExpressionToken($k, $v);
                        if (!$tmpToken->isCommaToken()) {
                            $localExpr->addToken($v);
                            $tmpExprList[] = $v;
                        } else {
                            // an expression could have multiple parts split by operands
                            // if we have a comma, it is a split-point for expressions
                            $tmpExprList = array_values($tmpExprList);
                            $localExprList = $this->processTokens($tmpExprList);
                            if (count($localExprList) > 1) {
                                $localExpr->setSubTree($localExprList);
                                $localExpr->setTokenType(ExpressionType::EXPRESSION);
                                $localExprList = $localExpr->toArray();
                                $localExprList['alias'] = false;
                                $localExprList = array($localExprList);
                            }
                            if (!$curr->getSubTree()) {
                                if (!empty($localExprList)) {
                                    $curr->setSubTree($localExprList);
                                }
                            } else {
                                $tmpExprList = $curr->getSubTree();
                                $curr->setSubTree(array_merge($tmpExprList, $localExprList));
                            }
                            $tmpExprList = array();
                            $localExpr = new ExpressionToken();
                        }
                    }
                    $tmpExprList = array_values($tmpExprList);
                    $localExprList = $this->processTokens($tmpExprList);
                    $curr->setTokenType(ExpressionType::BRACKET_EXPRESSION);
                    if (!$curr->getSubTree()) {
                        if (!empty($localExprList)) {
                            $curr->setSubTree($localExprList);
                        }
                    } else {
                        $tmpExprList = $curr->getSubTree();
                        $curr->setSubTree(array_merge($tmpExprList, $localExprList));
                    }
                }
            } elseif ($curr->isVariableToken()) {
                # a variable
                # it can be quoted
                $curr->setTokenType($this->getVariableType($curr->getUpper()));
                $curr->setSubTree(false);
                $curr->setNoQuotes(trim(trim($curr->getToken()), '@'), "`'\"");
            } else {
                /* it is either an operator, a colref or a constant */
                switch ($curr->getUpper()) {
                    case '*':
                        $curr->setSubTree(false); // o subtree
                        // single or first element of expression list -> all-column-alias
                        if (empty($resultList)) {
                            $curr->setTokenType(ExpressionType::COLREF);
                            break;
                        }
                        // if the last token is colref, const or expression
                        // then * is an operator
                        // but if the previous colref ends with a dot, the * is the all-columns-alias
                        if (!$prev->isColumnReference() && !$prev->isConstant() && !$prev->isExpression()
                            && !$prev->isBracketExpression() && !$prev->isAggregateFunction() && !$prev->isVariable()
                        ) {
                            $curr->setTokenType(ExpressionType::COLREF);
                            break;
                        }
                        if ($prev->isColumnReference() && $prev->endsWith(".")) {
                            $prev->addToken('*'); // tablealias dot *
                            continue 2; // skip the current token
                        }
                        $curr->setTokenType(ExpressionType::OPERATOR);
                        break;
                    case ':=':
                    case 'AND':
                    case '&&':
                    case 'BETWEEN':
                    case 'BINARY':
                    case '&':
                    case '~':
                    case '|':
                    case '^':
                    case 'DIV':
                    case '/':
                    case '<=>':
                    case '=':
                    case '>=':
                    case '>':
                    case 'IS':
                    case 'NOT':
                    case '<<':
                    case '<=':
                    case '<':
                    case 'LIKE':
                    case '%':
                    case '!=':
                    case '<>':
                    case 'REGEXP':
                    case '!':
                    case '||':
                    case 'OR':
                    case '>>':
                    case 'RLIKE':
                    case 'SOUNDS':
                    case 'XOR':
                    case 'IN':
                    case 'ANY':
                        $curr->setSubTree(false);
                        $curr->setTokenType(ExpressionType::OPERATOR);
                        break;
                    case 'NULL':
                    case 'TRUE':
                    case 'FALSE':
                        $curr->setSubTree(false);
                        $curr->setTokenType(ExpressionType::CONSTANT);
                        break;
                    case '-':
                    case '+':
                        // differ between preceding sign and operator
                        $curr->setSubTree(false);
                        if ($prev->isColumnReference() || $prev->isFunction() || $prev->isAggregateFunction()
                            || $prev->isConstant() || $prev->isSubQuery() || $prev->isExpression()
                            || $prev->isBracketExpression() || $prev->isVariable() || $prev->isCustomFunction()
                        ) {
                            $curr->setTokenType(ExpressionType::OPERATOR);
                        } else {
                            $curr->setTokenType(ExpressionType::SIGN);
                        }
                        break;
                    default:
                        $curr->setSubTree(false);
                        switch ($curr->getToken(0)) {
                            case "'":
                            case '"':
                                // it is a string literal
                                $curr->setTokenType(ExpressionType::STRING);
                                break;
                            case '`':
                                // it is an escaped column name
                                $curr->setTokenType(ExpressionType::COLREF);
                                $curr->setNoQuotes($curr->getToken());
                                break;
                            default:
                                if (is_numeric($curr->getToken())) {
                                    if ($prev->isSign()) {
                                        $prev->addToken($curr->getToken()); // it is a negative numeric constant
                                        $prev->setTokenType(ExpressionType::NUMBER);
                                        continue 3;
                                        // skip current token
                                    } else {
                                        $curr->setTokenType(ExpressionType::NUMBER);
                                    }
                                } else {
                                    $curr->setTokenType(ExpressionType::COLREF);
                                    $curr->setNoQuotes($curr->getToken());
                                }
                                break;
                        }
                }
            }


            /* is a reserved word? */
            if (!$curr->isOperator() && !$curr->isInList() && !$curr->isFunction() && !$curr->isAggregateFunction()
                && !$curr->isCustomFunction() && Constants::getInstance()->isReserved($curr->getUpper())
            ) {
                if (Constants::getInstance()->isCustomFunction($curr->getUpper())) {
                    $curr->setTokenType(ExpressionType::CUSTOM_FUNCTION);
                    $curr->setNoQuotes(null);
                } elseif (Constants::getInstance()->isAggregateFunction($curr->getUpper())) {
                    $curr->setTokenType(ExpressionType::AGGREGATE_FUNCTION);
                    $curr->setNoQuotes(null);
                } elseif (in_array($curr->getUpper(), ['NULL', 'FALSE', 'TRUE'])) {
                    // it is a reserved word, but we would like to set it as constant
                    $curr->setTokenType(ExpressionType::CONSTANT);
                    $curr->setNoQuotes(null);
                } else {
                    if (Constants::getInstance()->isParameterizedFunction($curr->getUpper())) {
                        // issue 60: check functions with parameters
                        // -> colref (we check parameters later)
                        // -> if there is no parameter, we leave the colref
                        $curr->setTokenType(ExpressionType::COLREF);
                    } elseif (Constants::getInstance()->isFunction($curr->getUpper())) {
                        $curr->setTokenType(ExpressionType::SIMPLE_FUNCTION);
                        $curr->setNoQuotes(null);
                    } else {
                        $curr->setTokenType(ExpressionType::RESERVED);
                        $curr->setNoQuotes(null);
                    }
                }
            }
            // issue 94, INTERVAL 1 MONTH
            if ($curr->isConstant() && Constants::getInstance()->isParameterizedFunction($prev->getUpper())) {
                $prev->setTokenType(ExpressionType::RESERVED);
                $prev->setNoQuotes(null);
            }
            if ($prev->isConstant() && Constants::getInstance()->isParameterizedFunction($curr->getUpper())) {
                $curr->setTokenType(ExpressionType::RESERVED);
                $curr->setNoQuotes(null);
            }
            if ($curr->isUnspecified()) {
                $curr->setTokenType(ExpressionType::EXPRESSION);
                $curr->setNoQuotes(null);
                $curr->setSubTree($this->processTokens($this->splitSQLIntoTokens($curr->getTrim())));
            }
            $resultList[] = $curr;
            $prev = $curr;
        } // end of for-loop
        return $resultList;
    }

    /**
     * This method removes parenthesis from start of the given string.
     * It removes also the associated closing parenthesis.
     */
    protected function removeParenthesisFromStart($token)
    {
        $parenthesisRemoved = 0;
        $trim = trim($token);
        if ($trim !== '' && $trim[0] === '(') { // remove only one parenthesis pair now!
            $parenthesisRemoved++;
            $trim[0] = ' ';
            $trim = trim($trim);
        }
        $parenthesis = $parenthesisRemoved;
        $i = 0;
        // Whether a string was opened or not, and with which character it was open (' or ")
        $stringOpened = '';
        while ($i < strlen($trim)) {
            if ($trim[$i] === "\\") {
                $i += 2; // an escape character, the next character is irrelevant
                continue;
            }
            if ($trim[$i] === "'") {
                if ($stringOpened === '') {
                    $stringOpened = "'";
                } elseif ($stringOpened === "'") {
                    $stringOpened = '';
                }
            }
            if ($trim[$i] === '"') {
                if ($stringOpened === '') {
                    $stringOpened = '"';
                } elseif ($stringOpened === '"') {
                    $stringOpened = '';
                }
            }
            if (($stringOpened === '') && ($trim[$i] === '(')) {
                $parenthesis++;
            }
            if (($stringOpened === '') && ($trim[$i] === ')')) {
                if ($parenthesis == $parenthesisRemoved) {
                    $trim[$i] = ' ';
                    $parenthesisRemoved--;
                }
                $parenthesis--;
            }
            $i++;
        }
        return trim($trim);
    }

    /**
     * Revokes the quoting characters from an expression
     * Possibibilies:
     *   `a`
     *   'a'
     *   "a"
     *   `a`.`b`
     *   `a.b`
     *   a.`b`
     *   `a`.b
     * It is also possible to have escaped quoting characters
     * within an expression part:
     *   `a``b` => a`b
     * And you can use whitespace between the parts:
     *   a  .  `b` => [a,b]
     */
    public function revokeQuotation($sql)
    {
        $tmp = trim($sql);
        $result = array();
        $quote = false;
        $start = 0;
        $i = 0;
        $len = strlen($tmp);
        while ($i < $len) {
            $char = $tmp[$i];
            switch ($char) {
                case '`':
                case '\'':
                case '"':
                    if ($quote === false) {
                        // start
                        $quote = $char;
                        $start = $i + 1;
                        break;
                    }
                    if ($quote !== $char) {
                        break;
                    }
                    if (isset($tmp[$i + 1]) && ($quote === $tmp[$i + 1])) {
                        // escaped
                        $i++;
                        break;
                    }
                    // end
                    $char = substr($tmp, $start, $i - $start);
                    $result[] = str_replace($quote . $quote, $quote, $char);
                    $start = $i + 1;
                    $quote = false;
                    break;
                case '.':
                    if ($quote === false) {
                        // we have found a separator
                        $char = trim(substr($tmp, $start, $i - $start));
                        if ($char !== '') {
                            $result[] = $char;
                        }
                        $start = $i + 1;
                    }
                    break;
                default:
                    // ignore
                    break;
            }
            $i++;
        }
        if ($quote === false && ($start < $len)) {
            $char = trim(substr($tmp, $start, $i - $start));
            if ($char !== '') {
                $result[] = $char;
            }
        }
        return array('delim' => (count($result) === 1 ? false : '.'), 'parts' => $result);
    }
}
