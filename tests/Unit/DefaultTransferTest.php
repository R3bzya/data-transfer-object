<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\TestCase;
use Rbz\DataTransfer\Tests\Unit\Transfers\DefaultTransfer;

class DefaultTransferTest extends TestCase
{
    /**
     * @dataProvider validData
     */
    public function testValidData(array $data)
    {
        $transfer = new DefaultTransfer();
        $load = $transfer->load($data);

        $this->assertTrue($load);
        $this->assertEquals($data, $transfer->toArray());
        $this->assertTrue($transfer->getErrors()->isEmpty());
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidData(array $data, array $errors)
    {
        $transfer = new DefaultTransfer();
        $load = $transfer->load($data);

        $this->assertFalse($load);
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
        $this->assertTrue($transfer->getErrors()->isNotEmpty());
    }

    public function testEmptyFormValidation()
    {
        $transfer = new DefaultTransfer();
        $validation = $transfer->validate();

        $this->assertFalse($validation);
        $this->assertEquals(3, $transfer->getErrors()->count());
    }

    public function testLoadedFormValidation()
    {
        $transfer = new DefaultTransfer();

        $transfer->a_one_s = 'string';
        $transfer->a_two_i = 1;
        $transfer->a_three_a = [];

        $validation = $transfer->validate();

        $this->assertTrue($validation);
        $this->assertTrue($transfer->getErrors()->isEmpty());
        $this->assertEquals(0, $transfer->getErrors()->count());
    }

    public function testValidateAttributes()
    {
        $transfer = new DefaultTransfer();
        $transfer->a_one_s = 'string';
        $transfer->a_two_i = 1;

        $validation = $transfer->validate(['a_two_a']);

        $this->assertFalse($validation);
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
                    'count' => 2
                ]
            ]
        ];
    }
}
