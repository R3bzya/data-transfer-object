<?php

namespace Rbz\Data\Tests\Unit\Validation;

use Rbz\Data\Support\Collection;
use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Support\Transfer\Rules;

class RuleHelperTest extends BaseCase
{
    /**
     * @dataProvider getPropertiesHasRules
     */
    public function testPropertiesHasRules(array $properties, array $result)
    {
        $rules = (new Rules([
            'property1' => ['value1'],
            'property2' => ['value2'],
            'property3' => ['value3'],
            'property4' => ['value4'],
        ]))->run(Rules::toValidation(Collection::make([
            'property1',
            'property2',
            'property3',
            'property4',
        ]), $properties));

        $this->assertEquals($result, $rules);
    }

    public function getPropertiesHasRules(): array
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
                    '__toExclude__', // Used to exclude all properties
                ],
                [
                    // can be empty, because we don`t have __toExclude__
                ]
            ],
        ];
    }
}
