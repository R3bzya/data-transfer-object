<?php

namespace Rbz\Data\Tests\Unit\Exploder;

use Rbz\Data\Exploder\Exploder;
use Rbz\Data\Tests\BaseCase;

class ExploderTest extends BaseCase
{
    /**
     * @dataProvider defaultProvider
     */
    public function testDefault(array $array, array $rules, array $expected)
    {
        $exploder = new Exploder();
        $result = $exploder->explode($array, $rules);

        $this->assertEquals($expected, $result);
    }

    public function defaultProvider(): array
    {
        return [
            [
                ['key_0' => [
                    ['key_1' => 'value_1'],
                    ['key_1' => 'value_2']
                ]],
                ['key_0.*.key_1' => 'string'],
                [
                    'key_0.0.key_1' => ['string'],
                    'key_0.1.key_1' => ['string'],
                ]
            ],
            [
                ['key_0' => [
                    'value_1',
                    'value_2'
                ]],
                ['key_0.*' => 'string'],
                [
                    'key_0.0' => ['string'],
                    'key_0.1' => ['string'],
                ]
            ],
            [
                ['key_0' => 'value'],
                ['key_0' => 'string'],
                ['key_0' => ['string']]
            ]
        ];
    }
}
