<?php

namespace Rbz\Data\Components;

use ArrayIterator;
use DomainException;
use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Components\PathInterface;
use Rbz\Data\Interfaces\Components\DataInterface;

class Data implements DataInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make($data = []): DataInterface
    {
        if (! self::accessible($data)) {
            throw new DomainException('The data must be an array or an Arrayable instance');
        }
        return $data instanceof Arrayable
            ? new self($data->toArray())
            : new self($data);
    }

    public static function accessible($data): bool
    {
        return is_array($data) || $data instanceof Arrayable;
    }

    public function add(string $key, $value = null): void
    {
        if ($this->has($key)) {
            throw new DomainException("The key `$key` exists");
        }
        $this->data[$key] = $value;
    }

    public function set(string $key, $value = null): void
    {
        if (! $this->has($key)) {
            throw new DomainException("The key `$key` not exists");
        }
        $this->data[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function get(string $key, $default = null)
    {
        return $this->getByPath($this->all(), Path::make($key), $default);
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->keys()->all());
    }

    public function only(array $keys): DataInterface
    {
        return self::make(array_filter_keys($this->all(), fn(string $key) => in_array($key, $keys)));
    }

    public function except(array $keys): DataInterface
    {
        return self::make(array_filter_keys($this->all(), fn(string $key) => ! in_array($key, $keys)));
    }

    public function replace(array $data): DataInterface
    {
        $this->data = $data;
        return $this;
    }

    public function count(): int
    {
        return count($this->all());
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
        return $this->all();
    }

    public function keys(): DataInterface
    {
        return new self(array_keys($this->all()));
    }

    /** TODO не нравится этот метод */
    public function getByPath(array $data, PathInterface $path,  $default)
    {
        foreach ($path as $key) {
            if ($this->has($key) && $path->isInternal()) {
                return $this->getByPath($this->get($key), $path->next(), $default);
            }
        }
        return $data[$path->asString()] ?? $default;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }
}
