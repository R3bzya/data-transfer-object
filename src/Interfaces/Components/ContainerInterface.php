<?php

namespace Rbz\Data\Interfaces\Components;

use Rbz\Data\Interfaces\TransferInterface;

interface ContainerInterface
{
    public function add(string $name, TransferInterface $transfer): void;

    public function get(string $name): TransferInterface;

    public function has(string $name): bool;

    public function getTransfers(): array;

    public function set(string $name, TransferInterface $transfer);

    public function load(array $transfers): void;

    public function isLoad(): bool;
}
