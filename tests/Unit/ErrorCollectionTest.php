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

    public function testOneNameDifferentPath()
    {
        $collection = $this->errorCollection();
        $firstError = ErrorItem::make(self::PROPERTY_FIRST, ['first message']);
        $secondError = ErrorItem::make(self::PROPERTY_FIRST, ['second message'], Path::make('second.first_property'));

        $this->assertEquals(2, $collection->addItem($firstError)->addItem($secondError)->count());
    }

    public function testIsEmpty()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());
    }

    public function testCount()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $collection->count());

        $collection->set(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $this->assertEquals(2, $collection->count());
    }

    public function testAdd()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(1, $collection->get(self::PROPERTY_FIRST)->count());

        $collection->set(self::PROPERTY_FIRST, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY_FIRST)->count());

        $collection->set(self::PROPERTY_FIRST, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY_FIRST)->count());
    }

    public function testAddItem()
    {
        $collection = $this->errorCollection();
        $collection->addItem(ErrorItem::make(self::PROPERTY_FIRST, (array) self::MESSAGE_ERROR));

        $this->assertEquals(1, $collection->count());

        $collection->addItem(ErrorItem::make(self::PROPERTY_SECOND, (array) self::MESSAGE_ERROR));

        $this->assertEquals(2, $collection->count());
    }

    public function testMerge()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->set(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $collection->merge($secondCollection);

        $this->assertEquals(2, $collection->count());
    }

    public function testWith()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->set(self::PROPERTY_SECOND, self::MESSAGE_ERROR);

        $newCollection = $collection->with($secondCollection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $newCollection->count());
    }

    public function testGet()
    {
        $collection = $this->errorCollection();

        $this->assertNull($collection->get(self::PROPERTY_FIRST));

        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::PROPERTY_FIRST, $collection->get(self::PROPERTY_FIRST)->getProperty());
    }

    public function testFirst()
    {
        $collection = $this->errorCollection();

        $this->assertNull($collection->first());

        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::PROPERTY_FIRST, $collection->first()->getProperty());
    }

    public function testGetFirstMessage()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals(self::MESSAGE_ERROR, $collection->getFirstMessage());
    }

    public function testHas()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertTrue($collection->has(self::PROPERTY_FIRST));
    }

    public function testKeys()
    {
        $collection = $this->errorCollection();
        $collection->set(self::PROPERTY_FIRST, self::MESSAGE_ERROR);

        $this->assertEquals([self::PROPERTY_FIRST], $collection->keys()->toArray());
    }
}
