<?php

namespace Rbz\Data\Components;

use DomainException;
use Rbz\Data\Interfaces\Components\ContainerInterface;
use Rbz\Data\Interfaces\TransferInterface;

class Container implements ContainerInterface
{
    /**
     * @var TransferInterface[]
     */
    private array $transfers = [];

    private bool $isLoad = false;

    public function add(string $name, TransferInterface $transfer): void
    {
        if (! $this->has($name)) {
            $this->transfers[$name] = $transfer;
        } else {
            throw (new DomainException("Transfer a key `$name` exist"));
        }
    }

    public function set(string $name, TransferInterface $transfer)
    {
        if ($this->has($name)) {
            $this->transfers[$name] = $transfer;
        } else {
            throw new DomainException("Transfer a key `$name` doesnt exist");
        }
    }

    public function get(string $name): TransferInterface
    {
        if ($this->has($name)) {
            return $this->transfers()[$name];
        }
        throw new DomainException("Transfer handler doesnt have `$name`");
    }

    public function has(string $name): bool
    {
        return key_exists($name, $this->transfers());
    }

    private function transfers(): array
    {
        return $this->transfers;
    }

    public function getTransfers(): array
    {
        return $this->transfers();
    }

    public function load(array $transfers): void
    {
        if (! empty($transfers)) {
            $this->loaded();
        }
        foreach ($transfers as $key => $class) {
            $this->add($key, call_user_func([$class, 'make']));
        }
    }

    public function isLoad(): bool
    {
        return $this->isLoad;
    }

    private function loaded(): void
    {
        $this->isLoad = true;
    }
}
