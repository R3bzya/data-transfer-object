<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validation\RuleHelper as ValidatorHelper;

/**
 * TODO названия тестов
 */
class RuleHelper extends BaseCase
{
    /**
     * @dataProvider getDefault
     */
    public function testHelper(array $properties, array $result)
    {
        $resolved = (new ValidatorHelper([
            'property1' => ['value1'],
            'property2' => ['value2'],
            'property3' => ['value3'],
            'property4' => ['value4'],
        ]))->resolve(ValidatorHelper::toValidation(Collection::make([
            'property1',
            'property2',
            'property3',
            'property4',
        ]), $properties));

        $this->assertEquals($result, $resolved);
    }

    /**
     * @dataProvider getNotDefault
     */
    public function testHelperNotDefault(array $properties, array $result)
    {
        $resolved = (new ValidatorHelper([
            'property1' => ['value1'],
            'property2' => ['value2'],
            'property3' => ['value3'],
        ]))->resolve(ValidatorHelper::toValidation(Collection::make([
            'property1',
            'property2',
            'property3',
            'property4',
        ]), $properties));

        $this->assertEquals($result, $resolved);
    }

    public function getDefault(): array
    {
        return [
            [
                [
                    '!property1'
                ],
                [
                    'property2' => ['value2'],
                    'property3' => ['value3'],
                    'property4' => ['value4'],
                ]
            ],
            [
                [
                    'property1'
                ],
                [
                    'property1' => ['value1'],
                ],
            ],
            [
                [
                    '!property1', 'property2'
                ],
                [
                    'property2' => ['value2'],
                ]
            ],
            [
                [
                    '!property5',
                ],
                [
                    'property1' => ['value1'],
                    'property2' => ['value2'],
                    'property3' => ['value3'],
                    'property4' => ['value4'],
                ]
            ],
            [
                [
                    'property5',
                ],
                [
                    // can be empty, because we don`t have property5
                ]
            ],
        ];
    }

    public function getNotDefault(): array
    {
        return [
            [
                [
                    'property4'
                ],
                [
                    'property4' => ['present'],
                ]
            ]
        ];
    }
}
