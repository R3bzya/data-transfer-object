<?php

namespace Rbz\Data\Tests\Unit\Exploder;

use Illuminate\Validation\ValidationRuleParser;
use Rbz\Data\Exploder\Exploder;
use Rbz\Data\Tests\BaseCase;

class ExploderTest extends BaseCase
{
    /**
     * @dataProvider defaultProvider
     */
    public function testDefault(array $rules, array $result)
    {
        $array = Exploder::explode([
            'key_1' => [
                'key_2' => [
                    'key_3' => 'value_3',
                    'key_4' => 'value_4',
                    'key_5' => []
                ],
                'key_6' => 'value_6',
                'key_7' => null,
            ]
        ], $rules);

        $this->assertEquals($result, $array);
    }

    public function defaultProvider(): array
    {
        return [
//            [
//                [
//                    'key_1.*.key_5.*' => ['string']
//                ],
//                [
//                    'key_1.key_2.key_5' => ['array'],
//                    'key_1.key_6' => ['array'],
//                    'key_1.key_7' => ['array'],
//                ]
//            ],
            [
                [
                    'key_1.key_6.key_8.*' => ['string']
                ],
                [
                    'key_1.key_6' => ['array'],
                ]
            ],
            [
                [
                    'key_1.*.*' => ['string']
                ],
                [
                    'key_1.key_2.key_3' => ['string'],
                    'key_1.key_2.key_4' => ['string'],
                    'key_1.key_2.key_5' => ['string'],
                    'key_1.key_6' => ['array'],
                    'key_1.key_7' => ['array'],
                ]
            ],
            [
                [
                    'key_1.key_2.*.key_8' => ['integer']
                ],
                [
                    'key_1.key_2.key_3' => ['array'],
                    'key_1.key_2.key_4' => ['array'],
                    'key_1.key_2.key_5.key_8' => ['integer'],
                ]
            ],
            [
                [
                    'key_1.key_2.*' => ['array']
                ],
                [
                    'key_1.key_2.key_3' => ['array'],
                    'key_1.key_2.key_4' => ['array'],
                    'key_1.key_2.key_5' => ['array'],
                ]
            ],
            [
                [
                    'key_1.key_2.key_3' => ['string'],
                    'key_1.key_2.key_3.key_4' => ['integer'],
                ],
                [
                    'key_1.key_2.key_3' => ['string'],
                    'key_1.key_2.key_3.key_4' => ['integer'],
                ]
            ]
        ];
    }
}
