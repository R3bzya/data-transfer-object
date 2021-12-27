<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;

class CollectionTest extends BaseCase
{
    public function testAdd()
    {
        $collection = $this->collection();
        $collection->add('test');

        $this->assertEquals([0 => 'test'], $collection->toArray());
        $this->assertEquals('test', $collection->get(0));
    }

    public function testRemove()
    {
        $collection = $this->collection(['test' => 'test',]);
        $collection->remove('test');

        $this->assertEquals([], $collection->toArray());
    }

    public function testGet()
    {
        $collection = $this->collection(['test' => 'test_2']);

        $this->assertEquals('test_2', $collection->get('test'));
    }

    public function testHas()
    {
        $collection = $this->collection(['test_1' => 'test_1', 2 => 'test_2', null => 'test_null']);

        $this->assertTrue($collection->has('test_1'));
        $this->assertTrue($collection->has(2));
        $this->assertTrue($collection->has(null));
        $this->assertFalse($collection->has('test_2'));
    }

    public function testOnly()
    {
        $collection = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test' => 'test_2'], $collection->only(['test'])->toArray());
    }

    public function testExcept()
    {
        $collection = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test_3' => 'test_4'], $collection->except(['test'])->toArray());
    }

    public function testCount()
    {
        $collection = $this->collection(['test' => 'test_2']);

        $this->assertEquals(1, $collection->count());
    }

    public function testKeys()
    {
        $collection = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test', 'test_3'], $collection->keys()->toArray());
    }
}
