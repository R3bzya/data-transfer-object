<?php

namespace Rbz\Data\Tests\Unit\Validators;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Validators\Helper as ValidatorHelper;

class Helper extends BaseCase
{
    /**
     * @dataProvider getTemp
     */
    public function testTemp(array $properties, array $result)
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

    public function getTemp()
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
}
