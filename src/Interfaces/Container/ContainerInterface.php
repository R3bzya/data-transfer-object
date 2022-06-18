<?php

namespace Rbz\Data\Interfaces\Container;

use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Support\Collectable;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface ContainerInterface extends Arrayable, Collectable
{
    /**
     * Add the transfer in the container by key.
     *
     * @param string $key
     * @param TransferInterface $transfer
     * @return void
     */
    public function add(string $key, TransferInterface $transfer): void;

    /**
     * Get the transfer from the container by key.
     *
     * @param string $key
     * @return TransferInterface
     */
    public function get(string $key): TransferInterface;

    /**
     * Determine if the transfer exists in the container by key.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all transfers from the container.
     *
     * @return TransferInterface[]
     */
    public function getTransfers(): array;

    /**
     * Get keys of the container.
     *
     * @return CollectionInterface
     */
    public function keys(): CollectionInterface;
}
