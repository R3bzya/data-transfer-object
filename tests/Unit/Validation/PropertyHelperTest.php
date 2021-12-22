<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\PropertyHelper;

class PropertyHelperTest extends BaseCase
{
    public function testBase()
    {
        $helper = new PropertyHelper([
            'test_1.test_1_1.test_1_2',
            'test_2.test_1_2',
            'test_3',
        ]);

        $this->assertEquals(['test_3'], $helper->get());
        $this->assertEquals([
            'test_1_1' => [
                'test_1_2'
            ]
        ], $helper->get('test_1'));
    }
}
