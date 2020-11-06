<?php

namespace EUAutomation\Canon\Value;

class ColumnValue implements Value
{
    /**
     * @var string[]
     */
    private $path;

    /**
     * ColumnValue constructor.
     * @param string[]|string $path
     */
    public function __construct($path)
    {
        $this->path = is_array($path) ? $path : [$path];
    }

    /**
     * @param $item
     * @return mixed|null
     */
    public function value($item)
    {
        $result = $item;

        foreach ($this->path as $segment) {
            if (!isset($result[$segment])) {
                return null;
            }
            $result = $result[$segment];
        }

        if(is_string($result)) {
            if(is_numeric($result)) {
                return floatval($result);
            }
            return mb_strtolower($result);
        }

        return $result;
    }
}
