<?php

namespace Rbz\Data\Support;

use ArrayIterator;
use Rbz\Data\Exceptions\CollectionException;
use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Traits\TypeCheckerTrait;

class Collection implements CollectionInterface
{
    use TypeCheckerTrait;

    private array $items;

    public function __construct($data = [])
    {
        $this->items = Arr::make($data);
    }

    public static function make($data = [])
    {
        return new static($data);
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
        return Arr::get($this->items(), $key, $default);
    }

    /**
     * @param mixed $key
     * @return static
     * @throws CollectionException
     */
    public function remove($key)
    {
        $this->assertKey($key);
        Arr::unset($this->items, $key);
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
        return Arr::has($this->items, $key);
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function in($value, bool $strict = false): bool
    {
        return Arr::in($this->items(), $value, $strict);
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
        return Arr::count($this->items());
    }

    public function isEmpty(): bool
    {
        return Arr::isEmpty($this->items());
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
        return new self(Arr::keys($this->items()));
    }

    public function getIterator(): ArrayIterator
    {
        return Arr::getIterator($this->items());
    }

    /**
     * @param mixed $data
     * @return static
     * @throws CollectionException
     */
    public function load($data)
    {
        foreach (Arr::make($data) as $key => $value) {
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
        $keys = new self($keys);
        return $this->filter(fn($value, $key) => $keys->in($key, true));
    }

    /**
     * @param array $keys
     * @return static
     */
    public function except(array $keys)
    {
        $keys = new self($keys);
        return $this->filter(fn($value, $key) => $keys->notIn($key, true));
    }

    public function filter(?callable $callable)
    {
        return new static(Arr::filter($this->items(), $callable, ARRAY_FILTER_USE_BOTH));
    }

    public function clear()
    {
        Arr::clear($this->items);
        return $this;
    }

    /**
     * @param callable|null $callable
     * @return static
     */
    public function map(?callable $callable)
    {
        $keys = $this->keys()->toArray();
        return new static(Arr::combine($keys, array_map($callable, $this->items(), $keys)));
    }

    public function mapWithKeys(callable $callable)
    {
        $items = [];
        foreach ($this->items() as $key => $item) {
            $items = Arr::merge($items, $callable($item, $key));
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
            if ($callable($item, $key) === false) {
                break;
            };
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
        return new static(Arr::flip($this->items()));
    }

    /**
     * @param Arrayable|mixed $data
     * @return static
     */
    public function merge($data)
    {
        $this->items = Arr::merge($this->items(), Arr::make($data));
        return $this;
    }

    /**
     * @param Arrayable|mixed $data
     * @return static
     */
    public function with($data)
    {
        return $this->clone()->merge($data);
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
        $this->items = Arr::make($data);
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
     */
    public function detach($key, $default = null)
    {
        return Arr::detach($this->items, $key, $default);
    }

    /**
     * @param mixed $data
     * @return static
     */
    public function diff($data)
    {
        return new static(Arr::diff($this->items(), Arr::make($data)));
    }

    public function slice(int $offset = 0, int $length = null, bool $preserveKeys = false)
    {
        return new static(Arr::slice($this->items(), $offset, $length, $preserveKeys));
    }

    public function first($default = null)
    {
        return Arr::first($this->items());
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

    public function toJson(): string
    {
        return json_encode($this->items());
    }

    public function dot()
    {
        return new static(Arr::dot($this->items()));
    }
}
