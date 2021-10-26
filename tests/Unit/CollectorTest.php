<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;

class CollectorTest extends BaseCase
{
    public function testCollectableLoad()
    {
        $transfer = $this->collectableTransfer();
        $transfer->load([
            'defaultTransfers' => [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    'a_one_s' => 'test',
                    'a_two_i' => 321,
                    'a_three_a' => ['test' => 'test'],
                ]
            ],
            'collections' => [
                [
                    'key_1' => 'value_1',
                    'key_2' => 'value_2',
                ],
                [
                    'another' => 'data',
                ],
                [
                    'more' => 'data',
                ]
            ]
        ]);

        $this->assertEquals(2, count($transfer->defaultTransfers));
        $this->assertEquals(3, count($transfer->collections));

        $this->assertEquals('string', $transfer->defaultTransfers[0]->a_one_s);
        $this->assertEquals('value_1', $transfer->collections[0]->get('key_1'));
    }
}
