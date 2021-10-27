<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface ContainerInterface extends Arrayable, Collectable
{
    public function add(string $name, TransferInterface $transfer): void;

    public function get(string $name): TransferInterface;

    public function has(string $name): bool;

    /**
     * @return TransferInterface[]
     */
    public function getTransfers(): array;

    public function set(string $name, TransferInterface $transfer);

    public function keys(): CollectionInterface;

    /**
     * TODO fix it
     * @return CollectionInterface|TransferInterface[]
     */
    public function toCollection(): CollectionInterface;
}
