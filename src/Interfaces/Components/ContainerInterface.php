<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface ContainerInterface extends Arrayable, Collectable
{
    public function add(string $id, TransferInterface $transfer): void;

    public function get(string $id): TransferInterface;

    public function has(string $id): bool;

    /**
     * @return TransferInterface[]
     */
    public function getTransfers(): array;

    public function keys(): CollectionInterface;
}
