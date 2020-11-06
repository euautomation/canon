<?php

namespace EUAutomation\Canon\Value;

class ColumnValueTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $item =  [
            'test' => 'testvalue'
        ];

        $columnValue = new ColumnValue('test');
        $result = $columnValue->value($item);
        $this->assertEquals('testvalue',$result);
    }
}
