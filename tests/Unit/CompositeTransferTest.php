<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Tests\Unit\Transfers\DefaultTransfer;

class CompositeTransferTest extends BaseCase
{
    public function testSetCompositeProperty()
    {
        $transfer = $this->compositeTransfer();

        $this->expectException(\TypeError::class);
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
     * @dataProvider getData
     */
    public function testLoad($data, $errors)
    {
        $transfer = $this->compositeTransfer();

        $this->assertEquals($errors['load'], $transfer->load($data));
        $this->assertEquals($errors['count'], $transfer->getErrors()->count());
    }

    public function testDeepLoading()
    {
        $transfer = $this->compositeTransfer();
        $transfer->load(['a_two_i' => 123]);
        $transfer->load(['default' => ['a_one_s' => 'string']]);

        $this->assertEquals(123, $transfer->default->a_two_i);
        $this->assertEquals('string', $transfer->default->a_one_s);
    }

    public function testHasProperty()
    {
        $transfer = $this->compositeTransfer();

        $this->assertTrue($transfer->hasProperty('default'));
        $this->assertTrue($transfer->hasProperty('b_one_s'));
        $this->assertTrue($transfer->hasProperty('test'));
    }

    public function getData(): array
    {
        return [
            [
                [
                    'a_two_i' => 1,
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'default' => [
                        'a_two_i' => 1
                    ],
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'b_one_s' => 'string',
                    'a_one_s' => 'string_2',
                    'a_two_i' => 1,
                    'a_three_a' => [],
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'b_one_s' => 'string',
                    'default' => [
                        'a_one_s' => 'string_2',
                        'a_two_i' => 1,
                        'a_three_a' => [],
                    ]
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'b_one_s' => 'string',
                    [
                        'a_one_s' => 'string_2',
                        'a_two_i' => 1,
                        'a_three_a' => [],
                    ]
                ],
                [
                    'load' => true,
                    'count' => 0
                ]
            ],
            [
                [
                    'default' => [
                        'a_one_s' => [],
                        'a_three_a' => [],
                    ]
                ],
                [
                    'load' => false,
                    'count' => 1
                ]
            ],
            [
                [
                    'b_one_s' => [],
                    'default' => [
                        'a_one_s' => 1,
                        'a_two_i' => [],
                        'a_three_a' => [],
                    ]
                ],
                [
                    'load' => false,
                    'count' => 2
                ]
            ]
        ];
    }
}
