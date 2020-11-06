<?php

namespace EUAutomation\Canon\Lexer;

use InvalidArgumentException;

/**
 * Class Lexer
 * @package EUAutomation\Canon\Lexer
 * @see https://github.com/greenlion/PHP-SQL-Parser/blob/master/src/PHPSQLParser/lexer/PHPSQLLexer.php
 */
class Lexer
{
    /**
     * @var Splitter
     */
    protected $splitters;

    /**
     * Lexer constructor.
     */
    public function __construct()
    {
        $this->splitters = new Splitter();
    }

    /**
     * Ends the given string $haystack with the string $needle?
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return boolean true, if the parameter $haystack ends with the character sequences $needle, false otherwise
     */
    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    /**
     * @param $sql
     * @return array
     */
    public function split($sql)
    {
        if (!is_string($sql)) {
            throw new InvalidArgumentException('$sql is not a string');
        }
        $tokens = array();
        $token = "";
        $splitLen = $this->splitters->getMaxLengthOfSplitter();
        $len = strlen($sql);
        $pos = 0;
        while ($pos < $len) {
            for ($i = $splitLen; $i > 0; $i--) {
                $substr = substr($sql, $pos, $i);
                if ($this->splitters->isSplitter($substr)) {
                    if ($token !== "") {
                        $tokens[] = $token;
                    }
                    $tokens[] = $substr;
                    $pos += $i;
                    $token = "";
                    continue 2;
                }
            }
            $token .= $sql[$pos];
            $pos++;
        }
        if ($token !== "") {
            $tokens[] = $token;
        }
        $tokens = $this->concatEscapeSequences($tokens);
        $tokens = $this->balanceBackticks($tokens);
        $tokens = $this->concatColReferences($tokens);
        $tokens = $this->balanceParenthesis($tokens);
        $tokens = $this->concatComments($tokens);
        $tokens = $this->concatUserDefinedVariables($tokens);
        $tokens = $this->concatScientificNotations($tokens);
        $tokens = $this->concatNegativeNumbers($tokens);
        return $tokens;
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function concatNegativeNumbers($tokens)
    {
        $i = 0;
        $cnt = count($tokens);
        $possibleSign = true;

        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }

            $token = $tokens[$i];

            // a sign is also possible on the first position of the tokenlist
            if ($possibleSign === true) {
                if ($token === '-' || $token === '+') {
                    if (is_numeric($tokens[$i + 1])) {
                        $tokens[$i + 1] = $token . $tokens[$i + 1];
                        unset($tokens[$i]);
                    }
                }
                $possibleSign = false;
                continue;
            }

            // TODO: we can have sign of a number after "(" and ",", are others possible?
            if (substr($token, -1, 1) === "," || substr($token, -1, 1) === "(") {
                $possibleSign = true;
            }

            $i++;
        }

        return array_values($tokens);
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function concatScientificNotations($tokens)
    {
        $i = 0;
        $cnt = count($tokens);
        $scientific = false;
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            $token = $tokens[$i];
            if ($scientific === true) {
                if ($token === '-' || $token === '+') {
                    $tokens[$i - 1] .= $tokens[$i];
                    $tokens[$i - 1] .= $tokens[$i + 1];
                    unset($tokens[$i]);
                    unset($tokens[$i + 1]);
                } elseif (is_numeric($token)) {
                    $tokens[$i - 1] .= $tokens[$i];
                    unset($tokens[$i]);
                }
                $scientific = false;
                continue;
            }
            if (strtoupper(substr($token, -1, 1)) === 'E') {
                $scientific = is_numeric(substr($token, 0, -1));
            }
            $i++;
        }
        return array_values($tokens);
    }


    /**
     * @param $tokens
     * @return array
     */
    protected function concatUserDefinedVariables($tokens)
    {
        $i = 0;
        $cnt = count($tokens);
        $userdef = false;
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            $token = $tokens[$i];
            if ($userdef !== false) {
                $tokens[$userdef] .= $token;
                unset($tokens[$i]);
                if ($token !== "@") {
                    $userdef = false;
                }
            }
            if ($userdef === false && $token === "@") {
                $userdef = $i;
            }
            $i++;
        }
        return array_values($tokens);
    }


    /**
     * @param $tokens
     * @return array
     */
    protected function concatComments($tokens)
    {
        $i = 0;
        $cnt = count($tokens);
        $comment = false;
        $inline = false;
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            $token = $tokens[$i];
            if ($comment !== false) {
                if ($inline === true && ($token === "\n" || $token === "\r\n")) {
                    $comment = false;
                } else {
                    unset($tokens[$i]);
                    $tokens[$comment] .= $token;
                }
                if ($inline === false && ($token === "*/")) {
                    $comment = false;
                }
            }
            if (($comment === false) && ($token === "--")) {
                $comment = $i;
                $inline = true;
            }
            if (($comment === false) && ($token === "/*")) {
                $comment = $i;
                $inline = false;
            }
            $i++;
        }
        return array_values($tokens);
    }

    /**
     * @param $token
     * @return bool
     */
    protected function isBacktick($token)
    {
        return ($token === "'" || $token === "\"" || $token === "`");
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function balanceBackticks($tokens)
    {
        $i = 0;
        $cnt = count($tokens);
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            $token = $tokens[$i];
            if ($this->isBacktick($token)) {
                $tokens = $this->balanceCharacter($tokens, $i, $token);
            }
            $i++;
        }
        return $tokens;
    }


    // backticks are not balanced within one token, so we have
    // to re-combine some tokens
    /**
     * @param $tokens
     * @param $idx
     * @param $char
     * @return array
     */
    protected function balanceCharacter($tokens, $idx, $char)
    {
        $token_count = count($tokens);
        $i = $idx + 1;
        while ($i < $token_count) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            $token = $tokens[$i];
            $tokens[$idx] .= $token;
            unset($tokens[$i]);
            if ($token === $char) {
                break;
            }
            $i++;
        }
        return array_values($tokens);
    }

    /**
     * This function concats some tokens to a column reference.
     * There are two different cases:
     *
     * 1. If the current token ends with a dot, we will add the next token
     * 2. If the next token starts with a dot, we will add it to the previous token
     *
     */
    protected function concatColReferences($tokens)
    {
        $cnt = count($tokens);
        $i = 0;
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }
            if ($tokens[$i][0] === ".") {
                // concat the previous tokens, till the token has been changed
                $k = $i - 1;
                $len = strlen($tokens[$i]);
                while (($k >= 0) && ($len == strlen($tokens[$i]))) {
                    if (!isset($tokens[$k])) { // FIXME: this can be wrong if we have schema . table . column
                        $k--;
                        continue;
                    }
                    $tokens[$i] = $tokens[$k] . $tokens[$i];
                    unset($tokens[$k]);
                    $k--;
                }
            }
            if ($this->endsWith($tokens[$i], '.') && !is_numeric($tokens[$i])) {
                // concat the next tokens, till the token has been changed
                $k = $i + 1;
                $len = strlen($tokens[$i]);
                while (($k < $cnt) && ($len == strlen($tokens[$i]))) {
                    if (!isset($tokens[$k])) {
                        $k++;
                        continue;
                    }
                    $tokens[$i] .= $tokens[$k];
                    unset($tokens[$k]);
                    $k++;
                }
            }
            $i++;
        }
        return array_values($tokens);
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function concatEscapeSequences($tokens)
    {
        $tokenCount = count($tokens);
        $i = 0;
        while ($i < $tokenCount) {
            if ($this->endsWith($tokens[$i], "\\")) {
                $i++;
                if (isset($tokens[$i])) {
                    $tokens[$i - 1] .= $tokens[$i];
                    unset($tokens[$i]);
                }
            }
            $i++;
        }
        return array_values($tokens);
    }

    /**
     * @param $tokens
     * @return array
     */
    protected function balanceParenthesis($tokens)
    {
        $token_count = count($tokens);
        $i = 0;
        while ($i < $token_count) {
            if ($tokens[$i] !== '(') {
                $i++;
                continue;
            }
            $count = 1;
            for ($n = $i + 1; $n < $token_count; $n++) {
                $token = $tokens[$n];
                if ($token === '(') {
                    $count++;
                }
                if ($token === ')') {
                    $count--;
                }
                $tokens[$i] .= $token;
                unset($tokens[$n]);
                if ($count === 0) {
                    $n++;
                    break;
                }
            }
            $i = $n;
        }
        return array_values($tokens);
    }
}
