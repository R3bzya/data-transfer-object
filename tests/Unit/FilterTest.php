<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Tests\BaseCase;
use Rbz\DataTransfer\Validators\Filter;

/** ToDo удалить testTemp */
class FilterTest extends BaseCase
{
//    public function testTemp()
//    {
//        $filter = new Filter(
//            ['1','2','3','4','5'],
//            ['6']
//        );
//
//        dd([
//            'properties' => $filter->properties(),
//            'all' => $filter->all(),
//            'array_keys' => $filter->filterArrayKeys(['1' => 123, '2' => 321]),
//            'filtered' =>$filter->filter(),
//            'include' => $filter->include(),
//            'exclude' => $filter->exclude()
//        ]);
//    }

    /**
     * @dataProvider getData
     */
    public function testFilter(array $properties, array $rules, array $result)
    {
        $filter = new Filter($properties, $rules);

        $this->assertEquals($result, $filter->filtered());
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
