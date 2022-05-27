<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\Validator;

class ValidatorTest extends BaseCase
{
    /**
     * @dataProvider correctConditions
     */
    public function testCorrectConditions(array $data, array $rules)
    {
        $this->assertTrue(Validator::make($data, $rules)->validate());
    }

//    /**
//     * @dataProvider incorrectConditions
//     */
//    public function testIncorrectConditions(array $data, array $rules)
//    {
//        $this->assertTrue(Validator::make($data, $rules)->validate());
//    }

    public function correctConditions(): array
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
            'bool' => [
                ['boolean_value' => false],
                ['boolean_value' => ['bool']]
            ],
            'empty_rules' => [
                ['value' => 'string'],
                []
            ]
        ];
    }

    public function incorrectConditions()
    {
        return [

        ];
    }
}
