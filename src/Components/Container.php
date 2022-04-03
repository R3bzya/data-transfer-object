<?php

namespace Rbz\Data\Components;

use Rbz\Data\Exceptions\ContainerException;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Components\Container\ContainerInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Support\Arr;

class Container implements ContainerInterface
{
    /**
     * @var TransferInterface[]
     */
    private array $transfers = [];

    public function add(string $key, TransferInterface $transfer): void
    {
        $this->transfers[$key] = $transfer;
    }

    public function get(string $key): TransferInterface
    {
        if (! $this->has($key)) {
            throw new ContainerException("Container doesnt have $key");
        }
        return $this->toCollection()->get($key);
    }

    public function has(string $key): bool
    {
        return $this->toCollection()->has($key);
    }

    public function transfers(): array
    {
        return $this->transfers;
    }

    public function getTransfers(): array
    {
        return $this->transfers();
    }

    public function toCollection(): CollectionInterface
    {
        return Arr::collect($this->transfers());
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
