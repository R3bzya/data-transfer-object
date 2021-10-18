<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;

class CollectionTest extends BaseCase
{
    public function testAdd()
    {
        $data = $this->collection();
        $data->add('test', 'test');

        $this->assertEquals(['test' => 'test'], $data->toArray());
    }

    public function testRemove()
    {
        $data = $this->collection(['test' => 'test',]);
        $data->remove('test');

        $this->assertEquals([], $data->toArray());
    }

    public function testGet()
    {
        $data = $this->collection(['test' => 'test_2']);

        $this->assertEquals('test_2', $data->get('test'));
    }

    public function testHas()
    {
        $data = $this->collection(['test' => 'test_2']);

        $this->assertTrue($data->has('test'));
        $this->assertFalse($data->has('test_2'));
    }

    public function testOnly()
    {
        $data = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test' => 'test_2'], $data->only(['test'])->toArray());
    }

    public function testExcept()
    {
        $data = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test_3' => 'test_4'], $data->except(['test'])->toArray());
    }

    public function testCount()
    {
        $data = $this->collection(['test' => 'test_2']);

        $this->assertEquals(1, $data->count());
    }

    public function testKeys()
    {
        $data = $this->collection(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test', 'test_3'], $data->keys()->toArray());
    }
}
