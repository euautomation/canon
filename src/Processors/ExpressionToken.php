<?php

namespace EUAutomation\Canon\Processors;

/**
 * Class ExpressionToken
 * @package EUAutomation\Canon\Processors
 * @see https://github.com/greenlion/PHP-SQL-Parser/blob/master/src/PHPSQLParser/utils/ExpressionToken.php
 */
class ExpressionToken
{
    /**
     * @var bool
     */
    private $subTree;
    /**
     * @var string
     */
    private $expression;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $token;
    /**
     * @var bool
     */
    private $tokenType;
    /**
     * @var string
     */
    private $trim;
    /**
     * @var string
     */
    private $upper;
    /**
     * @var null
     */
    private $noQuotes;

    /**
     * ExpressionToken constructor.
     * @param string $key
     * @param string $token
     */
    public function __construct($key = "", $token = "")
    {
        $this->subTree = false;
        $this->expression = "";
        $this->key = $key;
        $this->token = $token;
        $this->tokenType = false;
        $this->trim = trim($token);
        $this->upper = strtoupper($this->trim);
        $this->noQuotes = null;
    }
    # TODO: we could replace it with a constructor new ExpressionToken(this, "*")

    /**
     * @param $string
     */
    public function addToken($string)
    {
        $this->token .= $string;
    }

    /**
     * @return bool
     */
    public function isEnclosedWithinParenthesis()
    {
        return ($this->upper[0] === '(' && substr($this->upper, -1) === ')');
    }

    /**
     * @param $tree
     */
    public function setSubTree($tree)
    {
        $this->subTree = $tree;
    }

    /**
     * @return array|boolean
     */
    public function getSubTree()
    {
        return $this->subTree;
    }

    /**
     * @param bool $idx
     * @return string
     */
    public function getUpper($idx = false)
    {
        return $idx !== false ? $this->upper[$idx] : $this->upper;
    }

    /**
     * @param bool $idx
     * @return string
     */
    public function getTrim($idx = false)
    {
        return $idx !== false ? $this->trim[$idx] : $this->trim;
    }

    /**
     * @param bool $idx
     * @return string
     */
    public function getToken($idx = false)
    {
        return $idx !== false ? $this->token[$idx] : $this->token;
    }

    /**
     * @param $token
     * @param null $qchars
     */
    public function setNoQuotes($token, $qchars = null)
    {
        $this->noQuotes = ($token === null) ? null : $this->revokeQuotation($token);
    }

    public function getNoQuotes()
    {
        return $this->noQuotes;
    }

    /**
     * @param $type
     */
    public function setTokenType($type)
    {
        $this->tokenType = $type;
    }

    /**
     * @param $needle
     * @return bool
     */
    public function endsWith($needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        $start = $length * -1;
        return (substr($this->token, $start) === $needle);
    }

    /**
     * @return bool
     */
    public function isWhitespaceToken()
    {
        return ($this->trim === "");
    }

    /**
     * @return bool
     */
    public function isCommaToken()
    {
        return ($this->trim === ",");
    }

    /**
     * @return bool
     */
    public function isVariableToken()
    {
        return $this->upper[0] === '@';
    }

    /**
     * @return int
     */
    public function isSubQueryToken()
    {
        return preg_match("/^\\(\\s*SELECT/i", $this->trim);
    }

    /**
     * @return bool
     */
    public function isExpression()
    {
        return $this->tokenType === ExpressionType::EXPRESSION;
    }

    /**
     * @return bool
     */
    public function isBracketExpression()
    {
        return $this->tokenType === ExpressionType::BRACKET_EXPRESSION;
    }

    /**
     * @return bool
     */
    public function isOperator()
    {
        return $this->tokenType === ExpressionType::OPERATOR;
    }

    /**
     * @return bool
     */
    public function isInList()
    {
        return $this->tokenType === ExpressionType::IN_LIST;
    }

    /**
     * @return bool
     */
    public function isFunction()
    {
        return $this->tokenType === ExpressionType::SIMPLE_FUNCTION;
    }

    /**
     * @return bool
     */
    public function isUnspecified()
    {
        return ($this->tokenType === false);
    }

    /**
     * @return bool
     */
    public function isVariable()
    {
        return $this->tokenType === ExpressionType::GLOBAL_VARIABLE || $this->tokenType === ExpressionType::LOCAL_VARIABLE || $this->tokenType === ExpressionType::USER_VARIABLE;
    }

    /**
     * @return bool
     */
    public function isAggregateFunction()
    {
        return $this->tokenType === ExpressionType::AGGREGATE_FUNCTION;
    }

    /**
     * @return bool
     */
    public function isCustomFunction()
    {
        return $this->tokenType === ExpressionType::CUSTOM_FUNCTION;
    }

    /**
     * @return bool
     */
    public function isColumnReference()
    {
        return $this->tokenType === ExpressionType::COLREF;
    }

    /**
     * @return bool
     */
    public function isConstant()
    {
        return $this->tokenType === ExpressionType::CONSTANT;
    }

    /**
     * @return bool
     */
    public function isNull()
    {
        return $this->tokenType === ExpressionType::CONSTANT && $this->upper == 'NULL';
    }

    /**
     * @return bool
     */
    public function isString()
    {
        return $this->tokenType === ExpressionType::STRING;
    }

    /**
     * @return bool
     */
    public function isNumber()
    {
        return $this->tokenType === ExpressionType::NUMBER;
    }

    /**
     * @return bool
     */
    public function isSign()
    {
        return $this->tokenType === ExpressionType::SIGN;
    }

    /**
     * @param $token
     * @return array
     */
    private function revokeQuotation($token)
    {
        $defProc = new TokenProcessor();
        return $defProc->revokeQuotation($token);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        $result['expr_type'] = $this->tokenType;
        $result['base_expr'] = strtolower($this->token);
        if (!empty($this->noQuotes)) {
            $result['no_quotes'] = $this->noQuotes;
        }
        $result['sub_tree'] = $this->subTree;
        return $result;
    }
}
