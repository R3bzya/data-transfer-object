<?php

namespace Rbz\DataTransfer\Tests\Unit;

use Rbz\DataTransfer\Collections\Error\ErrorItem;
use Rbz\DataTransfer\Tests\BaseCase;

class ErrorCollectionTest extends BaseCase
{
    const PROPERTY = 'first_property';
    const PROPERTY_SECOND = 'second_property';
    const MESSAGE = 'Error message';

    public function testIsEmpty()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY, self::MESSAGE);

        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());
    }

    public function testCount()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY, self::MESSAGE);

        $this->assertEquals(1, $collection->count());

        $collection->add(self::PROPERTY_SECOND, self::MESSAGE);

        $this->assertEquals(2, $collection->count());
    }

    public function testAdd()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY, self::MESSAGE);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(1, $collection->get(self::PROPERTY)->count());

        $collection->add(self::PROPERTY, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY)->count());

        $collection->add(self::PROPERTY, 'Second message');

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $collection->get(self::PROPERTY)->count());
    }

    public function testAddItem()
    {
        $collection = $this->errorCollection();
        $collection->addItem(new ErrorItem(self::PROPERTY, (array) self::MESSAGE));

        $this->assertEquals(1, $collection->count());

        $collection->addItem(new ErrorItem(self::PROPERTY_SECOND, (array) self::MESSAGE));

        $this->assertEquals(2, $collection->count());
    }

    public function testMerge()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY, self::MESSAGE);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->add(self::PROPERTY_SECOND, self::MESSAGE);

        $collection->merge($secondCollection);

        $this->assertEquals(2, $collection->count());
    }

    public function testWith()
    {
        $collection = $this->errorCollection();
        $collection->add(self::PROPERTY, self::MESSAGE);

        $this->assertEquals(1, $this->count());

        $secondCollection = $this->errorCollection();
        $secondCollection->add(self::PROPERTY_SECOND, self::MESSAGE);

        $newCollection = $collection->with($secondCollection);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(2, $newCollection->count());
    }

    public function testGet()
    {
        $collection = $this->errorCollection();
        $this->expectException(\DomainException::class);
        $collection->get(self::PROPERTY);

        $collection->add(self::PROPERTY, self::MESSAGE);
        $this->assertEquals(self::PROPERTY, $collection->get(self::PROPERTY)->getProperty());
    }

    public function testGetFirst()
    {
        $collection = $this->errorCollection();
        $this->assertNull($collection->getFirst());

        $collection->add(self::PROPERTY, self::MESSAGE);
        $this->assertEquals(self::PROPERTY, $collection->getFirst()->getProperty());

        $collection->add(self::PROPERTY_SECOND, self::MESSAGE);
        $this->assertEquals(self::PROPERTY_SECOND, $collection->getFirst(self::PROPERTY_SECOND)->getProperty());
    }

    public function testGetFirstMessage()
    {
        $collection = $this->errorCollection();

        $collection->add(self::PROPERTY, self::MESSAGE);
        $this->assertEquals(self::MESSAGE, $collection->getFirstMessage());
    }
}
