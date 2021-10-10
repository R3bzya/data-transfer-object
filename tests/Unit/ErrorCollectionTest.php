<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Collections\Error\ErrorItem;
use Rbz\DataTransfer\Tests\BaseCase;

class ErrorCollectionTest extends BaseCase
{
    const PROPERTY_FIRST = 'first_property';
    const PROPERTY_SECOND = 'second_property';
    const MESSAGE_ERROR = 'Error message';

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
        $collection->addItem(new ErrorItem(self::PROPERTY_FIRST, (array) self::MESSAGE_ERROR));

        $this->assertEquals(1, $collection->count());

        $collection->addItem(new ErrorItem(self::PROPERTY_SECOND, (array) self::MESSAGE_ERROR));

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

        $this->expectException(\DomainException::class);

        $collection->get(self::PROPERTY_FIRST);

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

        $this->assertEquals([self::PROPERTY_FIRST], $collection->keys());
    }
}
