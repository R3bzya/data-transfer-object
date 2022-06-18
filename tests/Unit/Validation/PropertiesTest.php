<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Support\Transfer\Properties;
use Rbz\Data\Tests\BaseCase;

class PropertiesTest extends BaseCase
{
    public function testBase()
    {
        $helper = new Properties([
            'test_1.test_1_1.test_1_2',
            'test_2.test_1_2',
            'test_3',
        ]);

        $this->assertEquals(['test_3'], $helper->get());
        $this->assertEquals(['test_1' => ['test_1_1' => ['test_1_2']]], $helper->get(['test_1']));
        $this->assertEquals(['test_2' => ['test_1_2']], $helper->get(['test_2']));
    }
}
