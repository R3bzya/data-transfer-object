<?php

namespace Rbz\Data\Collections;

use ArrayIterator;
use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Traits\TypeCheckerTrait;

class Collection implements CollectionInterface
{
    use TypeCheckerTrait;

    private array $items;

    public function __construct($data = [])
    {
        $this->items = $this->getArrayFrom($data);
    }

    public static function make($data = [])
    {
        return new static($data);
    }

    public function getArrayFrom($value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        }
        return (array) $value;
    }

    /**
     * @param $value
     * @return static
     */
    public function add($value)
    {
        $this->items[] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return static
     */
    public function set(string $key, $value = null)
    {
        $this->items[$key] = $value;
        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->items()[$key] : $default;
    }

    public function remove(string $key): void
    {
        unset($this->items[$key]);
    }

    public function items(): array
    {
        return $this->items;
    }

    public function has(string $key): bool
    {
        return $this->keys()->in($key);
    }

    public function in($value, bool $strict = false): bool
    {
        return in_array($value, $this->items(), $strict);
    }

    public function notIn($value, bool $strict = false): bool
    {
        return ! in_array($value, $this->items(), $strict);
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
        return $this->count() > 0;
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

    public function load(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function only(array $keys)
    {
        $keys = Collection::make($keys);
        return $this->filter(fn($value, $key) => $keys->in($key, true));
    }

    public function except(array $keys)
    {
        $keys = Collection::make($keys);
        return $this->filter(fn($value, $key) => $keys->notIn($key, true));
    }

    public function filter(?callable $callable)
    {
        return static::make(array_filter($this->items(), $callable, ARRAY_FILTER_USE_BOTH));
    }

    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @param callable|null $callable
     * @return static
     */
    public function map(?callable $callable)
    {
        $keys = $this->keys()->toArray();
        return static::make(array_combine($keys, array_map($callable, $this->items(), $keys)));
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
        return static::make(array_flip($this->items()));
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
        $this->items = $data instanceof $this ? $data->items : $this->getArrayFrom($data);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return static
     */
    public function collect(string $key, $default = [])
    {
        return static::make($this->get($key, $default));
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function detach(string $key, $default = [])
    {
        $value = $this->get($key, $default);
        $this->remove($key);
        return $value;
    }
}
