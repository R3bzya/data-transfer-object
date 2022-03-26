<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\Validator;

class ValidatorTest extends BaseCase
{
    /**
     * @dataProvider testedCases
     */
    public function testValidation(array $data, array $rules)
    {
        $this->assertTrue(Validator::make($data, $rules)->validate());
    }

    public function testedCases(): array
    {
        return [
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
            ]
        ];
    }
}
