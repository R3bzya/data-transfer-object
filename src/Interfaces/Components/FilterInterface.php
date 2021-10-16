<?php

namespace Rbz\Data\Interfaces\Components;

use Rbz\Data\Interfaces\TransferInterface;

interface FilterInterface
{
    public static function getSeparator(): string;
    public function filterTransfer(TransferInterface $transfer): array;
    public function filterArray(array $array): array;
    public function filterArrayKeys(array $data): array;
    public function all(): array;
    public function getRules();
    public function filtered(): array;
}
