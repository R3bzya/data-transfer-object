<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\Support\Data;
use Rbz\Data\Validation\Support\RuleExploder;
use Rbz\Data\Validation\Validator;

class ValidatorTest extends BaseCase
{
    public function testValidation()
    {
        $this->assertTrue(Validator::make(['deep_array' => ['string']], ['deep_array.*' => ['string']])->validate());
    }

    public function testedCases(): array
    {
        return [
            'deep_array' => [
                ['deep_array' => ['string']],
                ['deep_array.*' => ['string']]
            ],
            'integer' => [
                ['integer_value' => 1],
                ['integer_value' => ['integer']]
            ],
            'numeric' => [
                ['numeric_value' => '1'],
                ['numeric_value' => ['numeric']]
            ],
            'string' => [
                ['string_value' => 'string'],
                ['string_value' => ['string']]
            ],
            'array' => [
                ['array_value' => []],
                ['array_value' => ['array']]
            ],
            'present' => [
                ['present_value' => 'present_value'],
                ['present_value' => ['present']]
            ],
        ];
    }

    /**
     * @dataProvider getExplode
     */
    public function testExplode(array $data, array $rules, array $result)
    {
        $exploded = (new RuleExploder(Data::encode($data)))->explode($rules);

        $this->assertEquals($result, $exploded);
    }

    public function getExplode(): array
    {
        return [
            [
                [
                    'key' => [
                        ['key_1' => 'value'],
                        ['key_1' => 'value'],
                        ['key_1' => 'value'],
                    ],
                ],
                [
                    'key.*.key_1' => ['string'],
                ],
                [
                    'key.0.key_1' => ['string'],
                    'key.1.key_1' => ['string'],
                    'key.2.key_1' => ['string'],
                ]
            ],
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
                    'key' => [
                        ['value_1', 'value_2'],
                        ['value_3', 'value_4'],
                    ],
                ],
                [
                    'key.*.*' => ['string'],
                ],
                [
                    'key.0.0' => ['string'],
                    'key.0.1' => ['string'],
                    'key.1.0' => ['string'],
                    'key.1.1' => ['string'],
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
            ]
        ];
    }
}
