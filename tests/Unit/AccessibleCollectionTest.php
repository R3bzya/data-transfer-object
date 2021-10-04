<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Collections\Accessible\AccessibleCollection;
use Rbz\DataTransfer\Tests\BaseCase;

class AccessibleCollectionTest extends BaseCase
{
    /**
     * @dataProvider dataFilter
     */
    public function testFilter(array $data, array $attributes, array $result)
    {
        $collection = new AccessibleCollection();
        $collection->load($data);

        $this->assertEquals($result, $collection->filter($attributes));
    }

    /**
     * @dataProvider dataFilterKeys
     */
    public function testFilterKeys(array $data, array $result)
    {
        $collection = new AccessibleCollection();
        $collection->load($data);

        $this->assertEquals($result, $collection->filterKeys([
            'include' => 123,
            'exclude' => '321',
            'test' => [123, '321']
        ]));
    }

    public function dataFilter(): array
    {
        return [
            [
                [
                    'include',
                    '!exclude',
                ],
                [
                    'include',
                    'exclude',
                    'test'
                ],
                [
                    'include',
                ]
            ],
            [
                [
                    'include',
                    '!exclude',
                    'test'
                ],
                [
                    'include',
                    'test',
                    'exclude'
                ],
                [
                    'include',
                    'test'
                ]
            ],
            [
                [
                    '!exclude',
                ],
                [
                    'include',
                    'test',
                    'exclude'
                ],
                [
                    'include',
                    'test'
                ]
            ]
        ];
    }

    public function dataFilterKeys(): array
    {
        return [
            [
                [
                    'include',
                    '!exclude',
                ],
                [
                    'include' => 123,
                ]
            ],
            [
                [
                    'include',
                    '!exclude',
                    'test'
                ],
                [
                    'include' => 123,
                    'test' => [123, '321']
                ]
            ],
            [
                [
                    '!exclude',
                ],
                [
                    'include' => 123,
                    'test' => [123, '321']
                ]
            ]
        ];
    }
}
