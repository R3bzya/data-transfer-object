<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\Support\Data;
use Rbz\Data\Validation\Support\Rule\Exploder;

class ExploderTest extends BaseCase
{
    /**
     * @dataProvider getExplode
     */
    public function testExplode(array $data, array $rules, array $result)
    {
        $exploded = Exploder::explode(Data::encode($data), $rules);

        $this->assertEquals($result, $exploded);
    }

    public function getExplode(): array
    {
        return [
            [
                [
                    'key' => [
                        ['key_1' => 'value'],
                        ['data']
                    ],
                ],
                [
                    'key.*' => ['string'],
                ],
                [
                    'key.0' => ['string'],
                    'key.1' => ['string'],
                ]
            ],
            [
                [
                    'key' => 123,
                ],
                [

                    'key' => ['integer']
                ],
                [
                    'key' => ['integer']
                ]
            ],
//            [
//                [
//                    'key' => [
//                        ['key_1' => 'value'],
//                        ['key_1' => 'value'],
//                        ['key_1' => 'value'],
//                    ],
//                ],
//                [
//                    'key.*.key_1' => ['string'],
//                ],
//                [
//                    'key.0.key_1' => ['string'],
//                    'key.1.key_1' => ['string'],
//                    'key.2.key_1' => ['string'],
//                ]
//            ],
//            [
//                [
//                    'key' => [
//                        ['value_1', 'value_2'],
//                        ['value_3', 'value_4'],
//                    ],
//                ],
//                [
//                    'key.*.*' => ['string'],
//                ],
//                [
//                    'key.0.0' => ['string'],
//                    'key.0.1' => ['string'],
//                    'key.1.0' => ['string'],
//                    'key.1.1' => ['string'],
//                ]
//            ],
        ];
    }
}
