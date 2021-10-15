<?php

namespace Rbz\Data\Components;

use DomainException;
use Illuminate\Contracts\Support\Arrayable;
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
            throw new \DomainException("The key `$key` exists");
        }
        $this->data[$key] = $value;
    }

    public function set(string $key, $value = null): void
    {
        if (! $this->has($key)) {
            throw new \DomainException("Key `$key` not exists");
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
        return $this->all()[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->keys());
    }

    public function only(array $keys): array
    {
        $only = [];
        foreach ($keys as $key) {
            $only[$key] = $this->get($key);
        }
        return $only;
    }

    public function except(array $keys): array
    {
        return array_filter_keys($this->all(), fn(string $key) => ! in_array($key, $keys));
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

    public function keys(): array
    {
        return array_keys($this->all());
    }
}
