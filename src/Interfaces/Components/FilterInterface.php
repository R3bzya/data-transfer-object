<?php

namespace Rbz\DataTransfer\Interfaces\Components;

use Rbz\DataTransfer\Interfaces\TransferInterface;

interface FilterInterface
{
    public function filterTransfer(TransferInterface $transfer): array;
    public function filterArray(array $array): array;
    public function filterArrayKeys(array $data): array;
    public function all(): array;
    public function getRules();
    public function filtered(): array;
}
