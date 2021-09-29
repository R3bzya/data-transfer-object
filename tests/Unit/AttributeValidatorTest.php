<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\TestCase;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;
use Rbz\DataTransfer\Validators\Rules\Validators\AttributeValidator;

class AttributeValidatorTest extends TestCase
{
    /**
     * @dataProvider ValidationPassedData
     */
    public function testValidationPassed(array $data, array $rules, array $attributes)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new AttributeValidator($transfer, $rules, $attributes);

        $this->assertTrue($validator->validate());
        $this->assertEquals(0, $validator->getErrors()->count());
    }

    /**
     * @dataProvider ValidationInvalidData
     */
    public function testValidationFailed(array $data, array $rules, array $attributes, $errors)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new AttributeValidator($transfer, $rules, $attributes);

        $this->assertFalse($validator->validate());
        $this->assertEquals($errors['count'], $validator->getErrors()->count());
    }

    public function ValidationPassedData(): array
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

    public function ValidationInvalidData(): array
    {
        return [
            [
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
            [
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
            [
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
