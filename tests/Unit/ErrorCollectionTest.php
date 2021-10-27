<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Collections\Error\ErrorItem;
use Rbz\Data\Components\Path;
use Rbz\Data\Tests\BaseCase;

class ErrorCollectionTest extends BaseCase
{
    const PROPERTY_FIRST = 'first_property';
    const PROPERTY_SECOND = 'second_property';
    const MESSAGE_ERROR = 'Error message';

//    public function testTemp()
//    {
//        $collection_1 = new Collection(Path::make('collection_1'));
//        $collection_2 = new Collection(Path::make('collection_2'));
//        $collection_3 = new Collection(Path::make('collection_3'));
//
//        $collection_1->add('1_1', ['какая-то ошибка 1_1']);
//        $collection_1->add('1_2', ['какая-то ошибка 1_2']);
//
//        $collection_2->add('2_1', ['какая-то ошибка 2_1']);
//        $collection_2->add('2_2', ['какая-то ошибка 2_2']);
//
//        $collection_3->add('3_1', ['какая-то ошибка 3_1']);
//        $collection_3->add('3_2', ['какая-то ошибка 3_2']);
//
//        $collection_1->merge($collection_2)->merge($collection_3);
//
//        $this->assertEquals([
//            "1_1" => [
//                "property" => "1_1",
//                "messages" => ["какая-то ошибка 1_1"],
//                "path" => "collection_1",
//            ],
//                "1_2" => [
//                "property" => "1_2",
//                "messages" => ["какая-то ошибка 1_2"],
//                "path" => "collection_1",
//            ],
//                "collection_1.collection_2.2_1" => [
//                "property" => "2_1",
//                "messages" => ["какая-то ошибка 2_1"],
//                "path" => "collection_1.collection_2",
//            ],
//                "collection_1.collection_2.2_2" => [
//                "property" => "2_2",
//                "messages" => ["какая-то ошибка 2_2"],
//                "path" => "collection_1.collection_2",
//            ],
//                "collection_1.collection_3.3_1" => [
//                "property" => "3_1",
//                "messages" => ["какая-то ошибка 3_1"],
//                "path" => "collection_1.collection_3",
//            ],
//                "collection_1.collection_3.3_2" => [
//                "property" => "3_2",
//                "messages" => ["какая-то ошибка 3_2"],
//                "path" => "collection_1.collection_3"
//            ]
//        ], $collection_1->toArray());
//    }
//
//    public function testTemp_2()
//    {
//        $collection_1 = new Collection(Path::make('collection_1'));
//        $collection_2 = new Collection(Path::make('collection_2'));
//        $collection_3 = new Collection(Path::make('collection_3'));
//
//        $collection_1->add('1_1', ['какая-то ошибка 1_1']);
//        $collection_1->add('1_2', ['какая-то ошибка 1_2']);
//
//        $collection_2->add('2_1', ['какая-то ошибка 2_1']);
//        $collection_2->add('2_2', ['какая-то ошибка 2_2']);
//
//        $collection_3->add('3_1', ['какая-то ошибка 3_1']);
//        $collection_3->add('3_2', ['какая-то ошибка 3_2']);
//
//        $collection_2->merge($collection_3);
//        $collection_1->merge($collection_2);
//
//        $this->assertEquals([
//            "1_1" => [
//                "property" => "1_1",
//                "messages" => ["какая-то ошибка 1_1"],
//                "path" => "collection_1",
//            ],
//            "1_2" => [
//                "property" => "1_2",
//                "messages" => ["какая-то ошибка 1_2"],
//                "path" => "collection_1",
//            ],
//            "collection_1.collection_2.2_1" => [
//                "property" => "2_1",
//                "messages" => ["какая-то ошибка 2_1"],
//                "path" => "collection_1.collection_2",
//            ],
//            "collection_1.collection_2.2_2" => [
//                "property" => "2_2",
//                "messages" => ["какая-то ошибка 2_2"],
//                "path" => "collection_1.collection_2",
//            ],
//            "collection_1.collection_2.collection_3.3_1" => [
//                "property" => "3_1",
//                "messages" => ["какая-то ошибка 3_1"],
//                "path" => "collection_1.collection_2.collection_3",
//            ],
//            "collection_1.collection_2.collection_3.3_2" => [
//                "property" => "3_2",
//                "messages" => ["какая-то ошибка 3_2"],
//                "path" => "collection_1.collection_2.collection_3"
//            ]
//        ], $collection_1->toArray());
//    }

    public function testIsEmpty()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());
    }

    public function testCount()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $collection->count());

        $collection->add(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $this->assertEquals(2, $collection->count());
    }

    public function testAdd()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(1, $collection->get(self::PROPERTY_FIRST)->count());

        $collection->add(self::PROPERTY_FIRST, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY_FIRST)->count());

        $collection->add(self::PROPERTY_FIRST, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY_FIRST)->count());
    }

    public function testAddItem()
    {
        $collection = $this->errorCollection();
        $collection->addItem(new ErrorItem(self::PROPERTY_FIRST, (array) self::MESSAGE_ERROR, Path::make(self::PROPERTY_FIRST)));

        $this->assertEquals(1, $collection->count());

        $collection->addItem(new ErrorItem(self::PROPERTY_SECOND, (array) self::MESSAGE_ERROR, Path::make(self::PROPERTY_SECOND)));

        $this->assertEquals(2, $collection->count());
    }

    public function testMerge()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->add(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $collection->merge($secondCollection);

        $this->assertEquals(2, $collection->count());
    }

    public function testWith()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->add(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $newCollection = $collection->with($secondCollection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $newCollection->count());
    }

    public function testGet()
    {
        $collection = $this->errorCollection();

        $this->assertNull($collection->get(self::PROPERTY_FIRST));

        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::PROPERTY_FIRST, $collection->get(self::PROPERTY_FIRST)->getProperty());
    }

    public function testGetFirst()
    {
        $collection = $this->errorCollection();

        $this->assertNull($collection->getFirst());

        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::PROPERTY_FIRST, $collection->getFirst()->getProperty());

        $collection->add(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $this->assertEquals(self::PROPERTY_SECOND, $collection->getFirst(self::PROPERTY_SECOND)->getProperty());
    }

    public function testGetFirstMessage()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::MESSAGE_ERROR, $collection->getFirstMessage());
    }

    public function testHas()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertTrue($collection->has(self::PROPERTY_FIRST));
    }

    public function testKeys()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals([self::PROPERTY_FIRST], $collection->keys()->toArray());
    }
}
