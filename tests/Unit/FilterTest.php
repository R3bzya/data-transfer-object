<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Components\Path;
use Rbz\Data\Tests\BaseCase;
use Rbz\Data\Components\Filter;

class FilterTest extends BaseCase
{
    public function testWithPath()
    {
        $filter = Filter::make(['1','2','3','4','5'])->setRules(['5'])->withPath(Path::make('composite'));

        $filter->getProperties();

        $this->assertTrue(false); // Filter ne rabotaet

        $this->assertEquals([
            'composite.1',
            'composite.2',
            'composite.3',
            'composite.4',
            'composite.5'
        ], $filter->getProperties());
    }

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
