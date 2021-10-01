<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\TestCase;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;
use Rbz\DataTransfer\Validators\Validator;

class AttributeValidatorTest extends TestCase
{
    /**
     * @dataProvider getValidationPassedData
     */
    public function testValidationPassed(array $data, array $rules, array $attributes)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new Validator($transfer, $rules, $attributes);

        $this->assertTrue($validator->validate());
        $this->assertEquals(0, $validator->getErrors()->count());
    }

    /**
     * @dataProvider getValidationInvalidData
     */
    public function testValidationFailed(array $data, array $rules, array $attributes, $errors)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new Validator($transfer, $rules, $attributes);

        $this->assertFalse($validator->validate());
        $this->assertEquals($errors['count'], $validator->getErrors()->count());
    }

    public function getValidationPassedData(): array
    {
        return [
            [
                [
                    'a_one_s' => 'sting',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    IsSetRule::class,
                    HasRule::class,
                ],
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a'
                ]
            ],
            [
                [
                    'a_one_s' => 'sting',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    'isset',
                    'has',
                ],
                []
            ],
            [
                [
                    'a_one_s' => 'sting',
                ],
                [
                    'isset',
                    HasRule::class,
                ],
                [
                    'a_one_s',
                ]
            ],
        ];
    }

    public function getValidationInvalidData(): array
    {
        return [
            'not set field, rule class is used' => [
                [
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    IsSetRule::class,
                    HasRule::class,
                ],
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a'
                ],
                [
                    'count' => 1
                ]
            ],
            'field is undefined, rule alias is used' => [
                [
                    'a_one_s' => 'sting',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                    'a_four_not_found' => 'test'
                ],
                [
                    'isset',
                    'has',
                ],
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a',
                    'a_four_not_found'
                ],
                [
                    'count' => 1
                ]
            ],
            'not set fields, rule mixed aliases is used' => [
                [
                    'a_one_s' => 'sting',
                ],
                [
                    'isset',
                    HasRule::class,
                ],
                [
                    'a_two_i',
                    'a_three_a'
                ],
                [
                    'count' => 2
                ]
            ],
        ];
    }
}
