<?php

namespace EUAutomation\Canon\Expression;

use ArrayAccess;

class Collection extends Expression implements ArrayAccess
{

    /**
     * @var Expression[]
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param $item
     */
    public function push($item)
    {
        $this->items[] = $item;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param $item
     * @return boolean
     */
    public function evaluate($item)
    {
        $previous = 'AND';
        $oring = false;
        foreach ($this->items as $expression) {
            $boolean = $expression->getBoolean();
            $result = $oring ? $oring : $expression->evaluate($item);

            if ($boolean == 'AND') {
                if (!$result && $previous == 'AND') {
                    return false;
                } elseif ($previous == 'OR') {
                    if (!$result) {
                        return false;
                    }
                    $oring = false;
                }
            } elseif (!$oring) {
                if ($result) {
                    $oring = true;
                }
            }

            $previous = $boolean;
        }
        return true;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
