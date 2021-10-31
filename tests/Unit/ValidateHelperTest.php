<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Helpers\ValidateHelper;
use Rbz\Data\Tests\BaseCase;

class ValidateHelperTest extends BaseCase
{
    /**
     * @dataProvider base
     */
    public function testBase(array $expected, array $properties)
    {
        $helper = new ValidateHelper($this->transfer());

        $this->assertEquals($expected, $helper->parse($properties));
    }

    /**
     * @dataProvider composite
     */
    public function testComposite(array $expected, array $properties)
    {
        $helper = new ValidateHelper($this->compositeTransfer()->default);

        $this->assertEquals($expected, $helper->parse($properties));
    }

    public function base(): array
    {
        return [
            [
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a'
                ],
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a'
                ],
            ],
            [
                [
                    '!a_one_s',
                ],
                [
                    '!a_one_s',
                ],
            ],
            [
                [
                    'a_one_s',
                ],
                [
                    'a_one_s',
                ],
            ],
            [
                [
                    'a_one_s',
                    '!a_one_s',
                ],
                [
                    'a_one_s',
                    '!a_one_s',
                    'undefined',
                    'undefined.second'
                ],
            ]
        ];
    }

    public function composite(): array
    {
        return [
            [
                [
                    '!a_three_a'
                ],
                [
                    'default.!a_three_a',
                    'custom.field',
                ]
            ],
            [
                [
                    '!a_one_s',
                    '!a_two_i',
                    '!a_three_a'
                ],
                [
                    '!default.a_three_a'
                ]
            ],
            [
                [
                    'a_three_a'
                ],
                [
                    'default.a_three_a'
                ]
            ],
            [
                [
                    'a_one_s',
                    'a_two_i',
                    'a_three_a'
                ],
                [
                    'default'
                ]
            ],
            [
                [
                    '!a_one_s',
                    '!a_two_i',
                    '!a_three_a'
                ],
                [
                    '!default'
                ]
            ],
        ];
    }
}
