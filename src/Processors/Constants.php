<?php

namespace EUAutomation\Canon\Processors;

use EUAutomation\Canon\Value\Functions\CleanFunction;
use EUAutomation\Canon\Value\Functions\LengthFunction;
use EUAutomation\Canon\Value\Functions\SubstrFunction;

/**
 * Class Constants
 * @package EUAutomation\Canon\Processors
 * @see https://github.com/greenlion/PHP-SQL-Parser/blob/master/src/PHPSQLParser/utils/PHPSQLParserConstants.php
 */
class Constants
{
    /**
     * @var null
     */
    private static $inst = null;
    /**
     * @var array
     */
    protected $customFunctions = [];
    /**
     * @var array
     */
    protected $reserved = ['ABS', 'ACOS', 'ADDDATE', 'ADDTIME', 'AES_ENCRYPT', 'AES_DECRYPT', 'AGAINST', 'ASCII',
        'ASIN', 'ATAN', 'AVG', 'BENCHMARK', 'BIN', 'BIT_AND', 'BIT_OR', 'BITCOUNT',
        'BITLENGTH', 'CAST', 'CEILING', 'CHAR', 'CHAR_LENGTH', 'CHARACTER_LENGTH', 'CHARSET',
        'COALESCE', 'COERCIBILITY', 'COLLATION', 'COMPRESS', 'CONCAT', 'CONCAT_WS',
        'CONNECTION_ID', 'CONV', 'CONVERT', 'CONVERT_TZ', 'COS', 'COT', 'COUNT', 'CRC32',
        'CURDATE', 'CURRENT_USER', 'CURRVAL', 'CURTIME', 'DATABASE', 'DATETIME', 'DATE_ADD',
        'DATE_DIFF', 'DATE_FORMAT', 'DATE_SUB', 'DAY', 'DAYNAME', 'DAYOFMONTH', 'DAYOFWEEK',
        'DAYOFYEAR', 'DECODE', 'DEFAULT', 'DEGREES', 'DES_DECRYPT', 'DES_ENCRYPT', 'ELT',
        'ENCODE', 'ENCRYPT', 'EXISTS', 'EXP', 'EXPORT_SET', 'EXTRACT', 'FIELD', 'FIND_IN_SET',
        'FLOOR', 'FORMAT', 'FOUND_ROWS', 'FROM_DAYS', 'FROM_UNIXTIME', 'GET_FORMAT',
        'GET_LOCK', 'GROUP_CONCAT', 'GREATEST', 'HEX', 'HOUR', 'IF', 'IFNULL', 'IN',
        'INET_ATON', 'INET_NTOA', 'INSERT', 'INSTR', 'INTERVAL', 'IS_FREE_LOCK',
        'IS_USED_LOCK', 'LAST_DAY', 'LAST_INSERT_ID', 'LCASE', 'LEAST', 'LEFT', 'LENGTH', 'LN',
        'LOAD_FILE', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCATE', 'LOG', 'LOG2', 'LOG10', 'LOWER',
        'LPAD', 'LTRIM', 'MAKE_SET', 'MAKEDATE', 'MAKETIME', 'MASTER_POS_WAIT', 'MATCH', 'MAX',
        'MD5', 'MICROSECOND', 'MID', 'MIN', 'MINUTE', 'MOD', 'MONTH', 'MONTHNAME', 'NEXTVAL',
        'NOW', 'NULLIF', 'OCT', 'OCTET_LENGTH', 'OLD_PASSWORD', 'ORD', 'PASSWORD',
        'PERIOD_ADD', 'PERIOD_DIFF', 'PI', 'POSITION', 'POW', 'POWER', 'QUARTER', 'QUOTE',
        'RADIANS', 'RAND', 'RELEASE_LOCK', 'REPEAT', 'REPLACE', 'REVERSE', 'RIGHT', 'ROUND',
        'ROW_COUNT', 'RPAD', 'RTRIM', 'SEC_TO_TIME', 'SECOND', 'SESSION_USER', 'SHA', 'SHA1',
        'SIGN', 'SOUNDEX', 'SPACE', 'SQRT', 'STD', 'STDDEV', 'STDDEV_POP', 'STDDEV_SAMP',
        'STRCMP', 'STR_TO_DATE', 'SUBDATE', 'SUBSTRING', 'SUBSTRING_INDEX', 'SUBTIME', 'SUM',
        'SYSDATE', 'SYSTEM_USER', 'TAN', 'TIME', 'TIMEDIFF', 'TIMESTAMP', 'TIMESTAMPADD',
        'TIMESTAMPDIFF', 'TIME_FORMAT', 'TIME_TO_SEC', 'TO_DAYS', 'TRIM', 'TRUNCATE', 'UCASE',
        'UNCOMPRESS', 'UNCOMPRESSED_LENGTH', 'UNHEX', 'UNIX_TIMESTAMP', 'UPPER', 'USER',
        'UTC_DATE', 'UTC_TIME', 'UTC_TIMESTAMP', 'UUID', 'VAR_POP', 'VAR_SAMP', 'VARIANCE',
        'VERSION', 'WEEK', 'WEEKDAY', 'WEEKOFYEAR', 'YEAR', 'YEARWEEK', 'ADD', 'ALL', 'ALTER',
        'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 'AUTO_INCREMENT', 'BDB', 'BEFORE',
        'BERKELEYDB', 'BETWEEN', 'BIGINT', 'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE',
        'CASE', 'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN', 'COLUMNS',
        'CONNECTION', 'CONSTRAINT', 'CONTINUE', 'CREATE', 'CROSS', 'CURRENT_DATE',
        'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURSOR', 'DATABASE', 'DATABASES', 'DAY_HOUR',
        'DAY_MICROSECOND', 'DAY_MINUTE', 'DAY_SECOND', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT',
        'DELAYED', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW',
        'DIV', 'DOUBLE', 'DROP', 'ELSE', 'ELSEIF', 'END', 'ENCLOSED', 'ESCAPED', 'EXISTS',
        'EXIT', 'EXPLAIN', 'FALSE', 'FETCH', 'FIELDS', 'FLOAT', 'FOR', 'FORCE', 'FOREIGN',
        'FOUND', 'FRAC_SECOND', 'FROM', 'FULLTEXT', 'GRANT', 'GROUP', 'HAVING',
        'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND', 'IF', 'IGNORE',
        'IN', 'INDEX', 'INFILE', 'INNER', 'INNODB', 'INOUT', 'INSENSITIVE', 'INSERT', 'INT',
        'INTEGER', 'INTERVAL', 'INTO', 'IO_THREAD', 'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS',
        'KILL', 'LEADING', 'LEAVE', 'LEFT', 'LIKE', 'LIMIT', 'LINES', 'LOAD', 'LOCALTIME',
        'LOCALTIMESTAMP', 'LOCK', 'LONG', 'LONGBLOB', 'LONGTEXT', 'LOOP', 'LOW_PRIORITY',
        'MASTER_SERVER_ID', 'MATCH', 'MEDIUMBLOB', 'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT',
        'MINUTE_MICROSECOND', 'MINUTE_SECOND', 'MOD', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG',
        'NULL', 'NUMERIC', 'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUT',
        'OUTER', 'OUTFILE', 'PRECISION', 'PRIMARY', 'PRIVILEGES', 'PROCEDURE', 'PURGE', 'READ',
        'REAL', 'REFERENCES', 'REGEXP', 'RENAME', 'REPEAT', 'REPLACE', 'REQUIRE', 'RESTRICT',
        'RETURN', 'REVOKE', 'RIGHT', 'RLIKE', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE',
        'SEPARATOR', 'SET', 'SHOW', 'SMALLINT', 'SOME', 'SONAME', 'SPATIAL', 'SPECIFIC', 'SQL',
        'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT', 'SQL_CALC_FOUND_ROWS',
        'SQL_SMALL_RESULT', 'SQL_TSI_DAY', 'SQL_TSI_FRAC_SECOND', 'SQL_TSI_HOUR',
        'SQL_TSI_MINUTE', 'SQL_TSI_MONTH', 'SQL_TSI_QUARTER', 'SQL_TSI_SECOND', 'SQL_TSI_WEEK',
        'SQL_TSI_YEAR', 'SSL', 'STARTING', 'STRAIGHT_JOIN', 'STRIPED', 'TABLE', 'TABLES',
        'TEMPORARY', 'TERMINATED', 'THEN', 'TIMESTAMPADD', 'TIMESTAMPDIFF', 'TINYBLOB',
        'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRUE', 'UNDO', 'UNION', 'UNIQUE', 'UNLOCK',
        'UNSIGNED', 'UPDATE', 'USAGE', 'USE', 'USER_RESOURCES', 'USING', 'UTC_DATE',
        'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES', 'VARBINARY', 'VARCHAR', 'VARCHARACTER',
        'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH', 'WRITE', 'XOR', 'YEAR_MONTH', 'ZEROFILL'];
    /**
     * @var array
     */
    protected $parameterizedFunctions = ['LENGTH', 'SUBSTR', 'CLEAN', 'STRIP'];
    /**
     * @var array
     */
    protected $functions = [
        'LENGTH' => LengthFunction::class,
        'SUBSTR' => SubstrFunction::class,
        'CLEAN' => CleanFunction::class,
        'STRIP' => CleanFunction::class,
    ];
    /**
     * @var array
     */
    protected $aggregateFunctions = [];

    /**
     * Call this method to get singleton
     *
     * @return Constants
     */
    public static function getInstance()
    {
        if (!isset(self::$inst)) {
            self::$inst = new Constants();
        }
        return self::$inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    {
        $this->reserved = array_flip($this->reserved);
        $this->aggregateFunctions = array_flip($this->aggregateFunctions);
        $this->parameterizedFunctions = array_flip($this->parameterizedFunctions);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isAggregateFunction($token)
    {
        return isset($this->aggregateFunctions[$token]);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isReserved($token)
    {
        return isset($this->reserved[$token]);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isFunction($token)
    {
        return isset($this->functions[$token]);
    }

    /**
     * @param $token
     * @return mixed|null
     */
    public function getFunctionClass($token)
    {
        return isset($this->functions[$token]) ? $this->functions[$token] : null;
    }

    /**
     * @param $token
     * @return bool
     */
    public function isParameterizedFunction($token)
    {
        return isset($this->parameterizedFunctions[$token]);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isCustomFunction($token)
    {
        return isset($this->customFunctions[$token]);
    }

    /**
     * @param $token
     */
    public function addCustomFunction($token)
    {
        $token = strtoupper(trim($token));
        $this->customFunctions[$token] = true;
    }

    /**
     * @param $token
     */
    public function removeCustomFunction($token)
    {
        $token = strtoupper(trim($token));
        unset($this->customFunctions[$token]);
    }

    /**
     * @return array
     */
    public function getCustomFunctions()
    {
        return array_keys($this->customFunctions);
    }
}
