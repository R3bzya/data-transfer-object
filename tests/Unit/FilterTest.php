<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Components\Filter;

class FilterTest extends BaseCase
{
    /**
     * @dataProvider getData
     */
    public function testFilter(array $properties, array $rules, array $result)
    {
        $filter = Filter::make($properties)->setRules($rules);

        $this->assertEquals($result, $filter->apply());
    }

    public function getData(): array
    {
        return [
            [
                ['1','2','3','4','5'],
                ['6'],
                []
            ],
            [
                ['1','2','3','4','5'],
                [],
                ['1','2','3','4','5']
            ],
            [
                ['1','2','3','4','5'],
                ['!1'],
                [1 => '2', 2 => '3', 3 => '4', 4 => '5'] // ToDo не уверен в надобности ключей
            ],
            [
                ['1','2','3','4','5'],
                ['1'],
                ['1']
            ],
        ];
    }
}
