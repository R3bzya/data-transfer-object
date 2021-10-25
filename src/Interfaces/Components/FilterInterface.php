<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\TransferInterface;

interface FilterInterface extends Arrayable
{
    public static function getSeparator(): string;
    public function filterTransfer(TransferInterface $transfer): array;
    public function filterArray(array $array): array;
    public function filterArrayKeys(array $data): array;
    public function getRules();
    public function filtered(): array;
    public function getInclude(): array;
    public function getExclude(): array;
    public function getProperties(): array;
}
