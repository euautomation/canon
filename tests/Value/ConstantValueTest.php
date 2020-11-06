<?php

namespace EUAutomation\Canon\Value;

use App\Models\FeedItem;

class ConstantValueTest extends \PHPUnit\Framework\TestCase
{
    public function testValue()
    {
        $item =  [
            'test' => 'testValue'
        ];

        $columnValue = new ConstantValue('test');
        $result = $columnValue->value($item);
        $this->assertEquals('test', $result);
    }
}
