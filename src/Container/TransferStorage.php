<?php

namespace Rbz\Data\Container;

use Rbz\Data\Exceptions\ContainerException;
use Rbz\Data\Interfaces\StorageInterface;
use Rbz\Data\Support\Arr;

class TransferStorage implements StorageInterface
{
    private array $transfers = [];
    
    public function toArray(): array
    {
        return $this->transfers;
    }
    
    public function add(string $key, $value): void
    {
        $this->transfers[$key] = $value;
    }
    
    public function remove(string $key): void
    {
        unset($this->transfers[$key]);
    }
    
    public function has(string $key): bool
    {
        return Arr::has($this->transfers, $key);
    }
    
    public function get(string $key)
    {
        if (! $this->has($key)) {
            throw new ContainerException("Container doesnt have $key");
        }
        return $this->transfers[$key];
    }
    
    public function count(): int
    {
        return Arr::count($this->transfers);
    }
    
    public function isEmpty(): bool
    {
        return Arr::isEmpty($this->transfers);
    }
}