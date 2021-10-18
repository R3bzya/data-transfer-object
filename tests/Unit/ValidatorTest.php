<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validators\Rules\Property\HasRule;
use Rbz\Data\Validators\Rules\Property\IsSetRule;
use Rbz\Data\Validators\Validator;

class ValidatorTest extends BaseCase
{
    /**
     * @dataProvider getValidationPassedData
     */
    public function testValidationPassed(array $data, array $rules)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new Validator($transfer, $rules);

        $this->assertTrue($validator->validate());
        $this->assertEquals(0, $validator->getErrors()->count());
    }

    /**
     * @dataProvider getValidationInvalidData
     */
    public function testValidationFailed(array $data, array $rules, $errors)
    {
        $transfer = $this->transfer();
        $transfer->load($data);

        $validator = new Validator($transfer, $rules);
        $validator->validate();

        $this->assertFalse($validator->validate());
        $this->assertEquals($errors['count'], $validator->getErrors()->count());
    }

    public function getValidationPassedData(): array
    {
        return [
            [
                [
                    'a_one_s' => 'string',
                ],
                [
                    'a_one_s' => [IsSetRule::class, HasRule::class],
                    '!a_two_i' => [IsSetRule::class, HasRule::class],
                    '!a_three_a' => [IsSetRule::class, HasRule::class]
                ],
            ],
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    [IsSetRule::class, HasRule::class],
                ],
            ],
            [
                [
                    'a_one_s' => 'string',
                ],
                [
                    'a_one_s' => ['isset', 'has'],
                ],
            ],
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    IsSetRule::class,
                    HasRule::class,
                ],
            ],
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    'isset',
                    'has',
                ],
            ],
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    'isset',
                    HasRule::class,
                ],
            ],
        ];
    }

    public function getValidationInvalidData(): array
    {
        return [
            'field is undefined, rule alias is used' => [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ],
                [
                    'isset',
                    'has',
                    'undefined_property' => ['has', 'isset'],
                ],
                [
                    'count' => 1
                ]
            ],
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
                    'count' => 1
                ]
            ],
            'not set fields, rule mixed aliases is used' => [
                [],
                [
                    'a_one_s' => ['isset', HasRule::class],
                    'a_two_i' => ['isset', HasRule::class],
                    'a_three_a' => ['isset', HasRule::class],
                ],
                [
                    'count' => 3
                ]
            ],
            'not set fields, rule mixed aliases is used_2' => [
                [],
                [
                    HasRule::class,
                    'isset',
                ],
                [
                    'count' => 3
                ]
            ],
        ];
    }
}
