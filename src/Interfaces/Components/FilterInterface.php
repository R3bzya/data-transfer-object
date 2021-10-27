<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\TransferInterface;

interface FilterInterface extends Arrayable
{
    public static function make(array $properties): FilterInterface;
    public function setRules(array $rules): FilterInterface;
    public static function getSeparator(): string;
    public function filterTransfer(TransferInterface $transfer): array;
    public function filterArray(array $data): array;
    public function getRules();
    public function apply(): array;
    public function getInclude(): array;
    public function getExclude(): array;
    public function getProperties(): array;
    public function setPath(PathInterface $path): void;

    public function getPath(): PathInterface;

    public function hasPath(): bool;
}
