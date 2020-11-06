<?php

namespace EUAutomation\Canon\Lexer;

/**
 * Class Splitter
 * @package EUAutomation\Canon\Lexer
 * @see https://github.com/greenlion/PHP-SQL-Parser/blob/master/src/PHPSQLParser/lexer/LexerSplitter.php
 */
class Splitter
{
    /**
     * @var array
     */
    protected static $splitters = array("<=>", "\r\n", "!=", ">=", "<=", "<>", "<<", ">>", ":=", "\\", "&&", "||", ":=",
        "/*", "*/", "--", ">", "<", "|", "=", "^", "(", ")", "\t", "\n", "'", "\"", "`",
        ",", "@", " ", "+", "-", "*", "/", ";");
    /**
     * @var int
     */
    protected $tokenSize;
    /**
     * @var array
     */
    protected $hashSet;

    /**
     * Constructor.
     *
     * It initializes some fields.
     */
    public function __construct()
    {
        $this->tokenSize = strlen(self::$splitters[0]); // should be the largest one
        $this->hashSet = array_flip(self::$splitters);
    }

    /**
     * Get the maximum length of a split token.
     *
     * The largest element must be on position 0 of the internal $_splitters array,
     * so the function returns the length of that token. It must be > 0.
     *
     * @return int The number of characters for the largest split token.
     */
    public function getMaxLengthOfSplitter()
    {
        return $this->tokenSize;
    }

    /**
     * Looks into the internal split token array and compares the given token with
     * the array content. It returns true, if the token will be found, false otherwise.
     *
     * @param String $token a string, which could be a split token.
     *
     * @return boolean true, if the given string will be a split token, false otherwise
     */
    public function isSplitter($token)
    {
        return isset($this->hashSet[$token]);
    }
}
