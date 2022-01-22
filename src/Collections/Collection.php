<?php

namespace Rbz\Data\Collections;

use ArrayIterator;
use Rbz\Data\Exceptions\CollectionException;
use Rbz\Data\Interfaces\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Traits\TypeCheckerTrait;

class Collection implements CollectionInterface
{
    use TypeCheckerTrait;

    private array $items;

    public function __construct($data = [])
    {
        $this->items = $this->makeArrayFrom($data);
    }

    public static function make($data = [])
    {
        return new static($data);
    }

    public function makeArrayFrom($value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        }
        return (array) $value;
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function add($value)
    {
        $this->items[] = $value;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return static
     * @throws CollectionException
     */
    public function set($key, $value = null)
    {
        $this->assertKey($key);
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws CollectionException
     */
    public function get($key, $default = null)
    {
        $this->assertKey($key);
        return $this->items[$key] ?? $default;
    }

    /**
     * @param mixed $key
     * @return static
     * @throws CollectionException
     */
    public function remove($key)
    {
        $this->assertKey($key);
        unset($this->items[$key]);
        return $this;
    }

    public function items(): array
    {
        return $this->items;
    }

    /**
     * @param mixed $key
     * @return bool
     * @throws CollectionException
     */
    public function has($key): bool
    {
        $this->assertKey($key);
        return key_exists($key, $this->items());
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function in($value, bool $strict = false): bool
    {
        return in_array($value, $this->items(), $strict);
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function notIn($value, bool $strict = false): bool
    {
        return ! $this->in($value, $strict);
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function toArray(): array
    {
        return $this->items();
    }

    public function keys(): CollectionInterface
    {
        return new self(array_keys($this->items()));
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items());
    }

    /**
     * @param mixed $data
     * @return static
     * @throws CollectionException
     */
    public function load($data)
    {
        foreach ($this->makeArrayFrom($data) as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    public function getItems(): array
    {
        return $this->items();
    }

    /**
     * @param array $keys
     * @return static
     */
    public function only(array $keys)
    {
        $keys = Collection::make($keys);
        return $this->filter(fn($value, $key) => $keys->in($key, true));
    }

    /**
     * @param array $keys
     * @return static
     */
    public function except(array $keys)
    {
        $keys = Collection::make($keys);
        return $this->filter(fn($value, $key) => $keys->notIn($key, true));
    }

    public function filter(?callable $callable)
    {
        return new static(array_filter($this->items(), $callable, ARRAY_FILTER_USE_BOTH));
    }

    public function clear()
    {
        $this->items = [];
        return $this;
    }

    /**
     * @param callable|null $callable
     * @return static
     */
    public function map(?callable $callable)
    {
        $keys = $this->keys()->toArray();
        return new static(array_combine($keys, array_map($callable, $this->items(), $keys)));
    }

    public function mapWithKeys(callable $callable)
    {
        $items = [];
        foreach ($this->items() as $key => $item) {
            $items = array_merge($items, $callable($item, $key));
        }
        return new static($items);
    }

    /**
     * @param callable $callable
     * @return static
     */
    public function each(callable $callable)
    {
        foreach ($this->items() as $key => $item) {
            $callable($item, $key);
        }
        return $this;
    }

    /**
     * @return static
     */
    public function clone()
    {
        return clone $this;
    }

    /**
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items()));
    }

    /**
     * @param CollectionInterface $collection
     * @return static
     */
    public function merge($collection)
    {
        $this->items = array_merge($this->items, $collection->getItems());
        return $this;
    }

    /**
     * @param CollectionInterface $collection
     * @return static
     */
    public function with($collection)
    {
        return $this->clone()->merge($collection);
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->add($value);
        } else {
            $this->set($offset, $value);
        }
    }

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    /**
     * @param mixed $data
     * @return static
     */
    public function replace($data)
    {
        $this->items = $data instanceof $this ? $data->items : $this->makeArrayFrom($data);
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return static
     * @throws CollectionException
     */
    public function collect($key, $default = [])
    {
        return new static($this->get($key, $default));
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     * @throws CollectionException
     */
    public function detach($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->remove($key);
        return $value;
    }

    /**
     * @param mixed $data
     * @return static
     */
    public function diff($data)
    {
        return new static(array_diff($this->items(), $this->makeArrayFrom($data)));
    }

    public function slice(int $offset = 0, int $length = null, bool $preserveKeys = false)
    {
        return new static(array_slice($this->items, $offset, $length, $preserveKeys));
    }

    public function first($default = null)
    {
        foreach ($this->items() as $item) {
            return $item;
        }
        return $default;
    }

    /**
     * @param mixed $key
     * @return void
     * @throws CollectionException
     */
    protected function assertKey($key): void
    {
        if (! is_scalar($key) && ! is_null($key)) {
            throw new CollectionException('Key type must be a scalar or null, ' . gettype($key) . ' given');
        }
    }
}
