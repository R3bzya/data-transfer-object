<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Exceptions\PropertyException;
use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Tests\Unit\Transfers\DefaultTransfer;

class CompositeTransferTest extends BaseCase
{
    public function testSetCompositeProperty()
    {
        $transfer = $this->compositeTransfer();

        $this->expectException(PropertyException::class);
        $transfer->default = [];

        $transfer->default = DefaultTransfer::make(['a_one_s' => 'its okay']);
        $this->assertEquals('its okay', $transfer->default->a_one_s);
    }

    public function testValidation()
    {
        $transfer = $this->compositeTransfer();
        $transfer->b_one_s = 1;
        $transfer->default->a_one_s = 'string';

        $this->assertFalse($transfer->validate());
        $this->assertEquals(2, $transfer->getErrors()->count());
        $this->assertTrue($transfer->getErrors()->has('default.a_three_a'));
        $this->assertTrue($transfer->getErrors()->has('default.a_three_a'));
    }

    public function testSetSelfProperty()
    {
        $transfer = $this->compositeTransfer();
        $transfer->b_one_s = 'set';

        $this->assertEquals('set', $transfer->b_one_s);
    }

    public function testSetToCompositeProperty()
    {
        $transfer = $this->compositeTransfer();
        $transfer->default->a_one_s = 'set';

        $this->assertEquals('set', $transfer->default->a_one_s);
    }

    /**
     * @dataProvider validData
     */
    public function testValidData($data)
    {
        $transfer = $this->compositeTransfer();

        $this->assertTrue($transfer->load($data));
        $this->assertEquals(0, $transfer->getErrors()->count());

        $this->assertEquals(
            [
                'b_one_s' => 'string',
                'default' => $transfer->default
            ],
            $transfer->toArray()
        );
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidData($data, $errors)
    {
        $transfer = $this->compositeTransfer();

        $this->assertFalse($transfer->load($data));
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
    }

    public function validData()
    {
        return [
            [
                [
                    'b_one_s' => 'string',
                    'a_one_s' => 'string_2',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [
                    'b_one_s' => 'string',
                    'default' => [
                        'a_one_s' => 'string_2',
                        'a_two_i' => 1,
                        'a_three_a' => [],
                    ]
                ],
                [
                    'b_one_s' => 'string',
                    [
                        'a_one_s' => 'string_2',
                        'a_two_i' => 1,
                        'a_three_a' => [],
                    ]
                ],
            ]
        ];
    }

    public function invalidData()
    {
        return [
            [
                [
                    'default' => [
                        'a_one_s' => [],
                        'a_three_a' => [],
                    ]
                ],
                [
                    'count' => 3
                ]
            ],
            [
                [
                    'b_one_s' => 'string',
                    'default' => [
                        'a_one_s' => 1,
                        'a_two_i' => [],
                        'a_three_a' => [],
                    ]
                ],
                [
                    'count' => 1
                ]
            ]
        ];
    }
}
