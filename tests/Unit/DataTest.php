<?php

namespace Rbz\Data\Tests\Unit;

use Rbz\Data\Tests\BaseCase;

class DataTest extends BaseCase
{
    public function testAdd()
    {
        $data = $this->data();
        $data->add('test', 'test');

        $this->assertEquals(['test' => 'test'], $data->all());
    }

    public function testFailedAdd()
    {
        $data = $this->data();
        $data->add('test', 'test');

        $this->expectException(\DomainException::class);

        $data->add('test', 'test');
    }

    public function testSet()
    {
        $data = $this->data(['test' => 'test']);
        $data->set('test', 'test_2');

        $this->assertEquals(['test' => 'test_2'], $data->all());
    }

    public function testFailedSet()
    {
        $data = $this->data();

        $this->expectException(\DomainException::class);

        $data->set('test', 'test_2');
    }

    public function testRemove()
    {
        $data = $this->data(['test' => 'test']);
        $data->remove('test');

        $this->assertEquals([], $data->all());
    }

    public function testGet()
    {
        $data = $this->data([
            'test' => [
                'test_2' => 'test_3'
            ]
        ]);

        $this->assertEquals('test_3', $data->get('test.test_2'));
    }

    public function testHas()
    {
        $data = $this->data(['test' => 'test_2']);

        $this->assertTrue($data->has('test'));
        $this->assertFalse($data->has('test_2'));
    }

    public function testOnly()
    {
        $data = $this->data(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test' => 'test_2'], $data->only(['test'])->all());
    }

    public function testExcept()
    {
        $data = $this->data(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test_3' => 'test_4'], $data->except(['test'])->all());
    }

    public function testReplace()
    {
        $data = $this->data(['test' => 'test_2']);
        $data->replace(['test_3' => 'test_4']);

        $this->assertEquals(['test_3' => 'test_4'], $data->all());
    }

    public function testCount()
    {
        $data = $this->data(['test' => 'test_2']);

        $this->assertEquals(1, $data->count());
    }

    public function testKeys()
    {
        $data = $this->data(['test' => 'test_2', 'test_3' => 'test_4']);

        $this->assertEquals(['test', 'test_3'], $data->keys()->toArray());
    }
}
