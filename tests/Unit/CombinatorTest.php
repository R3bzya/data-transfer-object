<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\BaseCase;

class CombinatorTest extends BaseCase
{
    public function testCombinationsLoad()
    {
        $transfer = $this->combinedTransfer();
        $transfer->load(['combined' => [
            [
                'a_one_s' => 'sting',
                'a_two_i' => 123,
                'a_three_a' => [],
            ],
            [
                'a_one_s' => 'test',
                'a_two_i' => 321,
                'a_three_a' => ['test' => 'test'],
            ]
        ]]);

        //dd($transfer->getCombinator()->getCombinations(), $transfer->combined, $transfer->getErrors()->toArray());

        $this->assertTrue(false);
    }
}
