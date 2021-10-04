<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\BaseCase;

class TransferTest extends BaseCase
{
    /**
     * @dataProvider getValidData
     */
    public function testValidData(array $data)
    {
        $transfer = $this->transfer();

        $this->assertTrue($transfer->load($data));
        $this->assertEquals($data, $transfer->toArray());
        $this->assertTrue($transfer->getErrors()->isEmpty());
    }

    /**
     * @dataProvider getInvalidData
     */
    public function testInvalidData(array $data, array $errors)
    {
        $transfer = $this->transfer();

        $this->assertEquals($errors['load'], $transfer->load($data));
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
    }

    /**
     * @dataProvider getCustomRulesData
     */
    public function testCustomRules(array $data, array $properties, array $errors)
    {
        $transfer = $this->transferWithCustomRules();
        $transfer->load($data);

        $this->assertEquals($errors['validate'], $transfer->validate($properties));
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
    }

    public function testEmptyTransferValidation()
    {
        $transfer = $this->transfer();

        $this->assertFalse($transfer->validate());
        $this->assertEquals(count($transfer->getProperties()), $transfer->getErrors()->count());
    }

    public function testLoadedTransferValidation()
    {
        $transfer = $this->transfer();
        $transfer->a_one_s = 'string';
        $transfer->a_two_i = 1;
        $transfer->a_three_a = [];

        $this->assertTrue($transfer->validate());
        $this->assertTrue($transfer->getErrors()->isEmpty());
        $this->assertEquals(0, $transfer->getErrors()->count());
    }

    public function testValidateAttributes()
    {
        $transfer = $this->transfer();
        $transfer->a_one_s = 'string';
        $transfer->a_two_i = 1;

        $this->assertFalse($transfer->validate(['undefined_property']));
        $this->assertEquals(1, $transfer->getErrors()->count());
    }

    public function getValidData(): array
    {
        return [
            [
                [
                    'a_one_s' => 'sting',
                    'a_two_i' => 123,
                    'a_three_a' => [],
                ]
            ],
            [
                [
                    'a_one_s' => 123,
                    'a_two_i' => '123',
                    'a_three_a' => ['test' => 'test'],
                ]
            ]
        ];
    }

    public function getInvalidData(): array
    {
        return [
            'empty data' => [
                [],
                [
                    'load' => false,
                    'count' => 3
                ]
            ],
            'bad types' => [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 'string',
                    'a_three_a' => 123,
                ],
                [
                    'load' => false,
                    'count' => 2
                ]
            ],
            'not all fields' => [
                [
                    'a_one_s' => 'string',
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ]
        ];
    }

    public function getCustomRulesData(): array
    {
        return [
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [
                    'a_one_s',
                ],
                [
                    'validate' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'a_one_s' => 'st',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [
                    'a_two_i',
                ],
                [
                    'validate' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'a_one_s' => 'string',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [],
                [
                    'validate' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'a_one_s' => 'st',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [
                    'a_one_s',
                ],
                [
                    'validate' => false,
                    'count' => 1
                ]
            ],
            [
                [
                    'a_one_s' => 'st',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [],
                [
                    'validate' => false,
                    'count' => 1
                ]
            ],
        ];
    }
}
