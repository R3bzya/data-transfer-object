<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\TestCase;

class TransferTest extends TestCase
{
    /**
     * @dataProvider validData
     */
    public function testValidData(array $data)
    {
        $transfer = $this->transfer();

        $this->assertTrue($transfer->load($data));
        $this->assertEquals($data, $transfer->toArray());
        $this->assertTrue($transfer->getErrors()->isEmpty());
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidData(array $data, array $errors)
    {
        $transfer = $this->transfer();

        $this->assertEquals($errors['load'], $transfer->load($data));
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
    }

    public function testEmptyFormValidation()
    {
        $transfer = $this->transfer();

        $this->assertFalse($transfer->validate());
        $this->assertEquals(count($transfer->getAttributes()), $transfer->getErrors()->count());
    }

    public function testLoadedFormValidation()
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

        $this->assertFalse($transfer->validate(['a_two_a']));
        $this->assertEquals(1, $transfer->getErrors()->count());
    }

    public function validData(): array
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

    public function invalidData(): array
    {
        return [
            [
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
            [
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
}
