<?php

namespace Rbz\Data\Collections;

use ArrayIterator;
use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;

class Collection implements CollectionInterface
{
    private array $items;

    public function __construct($value = [])
    {
        $this->items = self::getArrayFrom($value);
    }

    public function add(string $key, $value = null): void
    {
        $this->items[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        if (! $this->has($key)) {
            return $default;
        }
        return $this->items()[$key];
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
        return in_array($key, $this->keys()->toArray());
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
            $this->add($key, $value);
        }
    }

    public function getItems(): array
    {
        return $this->items();
    }

    public function only(array $keys)
    {
        return new static(array_filter_keys($this->items(), fn(string $key) => in_array($key, $keys)));
    }

    public function except(array $keys)
    {
        return new static(array_filter_keys($this->items(), fn(string $key) => ! in_array($key, $keys)));
    }

    public function filterKeys(?callable $callable)
    {
        return new static(array_filter($this->items(), $callable, ARRAY_FILTER_USE_KEY));
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public static function getArrayFrom($value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        }
        return (array) $value;
    }
}
