<?php

namespace Rbz\Data\Components\Container;

use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Components\Container\ContainerInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Support\Arr;

/**
 * TODO посмотреть на реализацию евентов, нужно привести к такомуже виду
 */
class TransferManager implements ContainerInterface
{
    private TransferStorage $storage;
    
    public function __construct()
    {
        $this->storage = new TransferStorage();
    }

    public function add(string $key, TransferInterface $transfer): void
    {
        $this->storage->add($key, $transfer);
    }

    public function get(string $key): TransferInterface
    {
        return $this->storage->get($key);
    }

    public function has(string $key): bool
    {
        return $this->storage->has($key);
    }

    public function getTransfers(): array
    {
        return $this->storage->toArray();
    }

    public function toCollection(): CollectionInterface
    {
        return Arr::collect($this->toArray());
    }

    public function toArray(): array
    {
        return $this->getTransfers();
    }

    public function keys(): CollectionInterface
    {
        return $this->toCollection()->keys();
    }
}
