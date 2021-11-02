<?php

namespace Rbz\Data\Components;

use DomainException;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Components\ContainerInterface;
use Rbz\Data\Interfaces\TransferInterface;

class Container implements ContainerInterface
{
    /**
     * @var TransferInterface[]
     */
    private array $transfers = [];

    public function __construct(array $transfers)
    {
        foreach ($transfers as $key => $class) {
            $this->add($key, call_user_func([$class, 'make']));
        }
    }

    public function add(string $name, TransferInterface $transfer): void
    {
        $this->transfers[$name] = $transfer->withPath(Path::make($name));
    }

    public function get(string $name): TransferInterface
    {
        if (! $this->has($name)) {
            throw new DomainException("Container doesnt have `$name`");
        }
        return $this->transfers()[$name];
    }

    public function has(string $name): bool
    {
        return $this->toCollection()->has($name);
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
        return Collection::make($this->getTransfers());
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
