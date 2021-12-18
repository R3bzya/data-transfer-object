<?php

namespace Rbz\Data\Interfaces\Components\Filter;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\TransferInterface;

/**
 * @deprecated
 */
interface FilterInterface extends Arrayable
{
    public static function make(array $properties): FilterInterface;
    public function setRules(array $rules): FilterInterface;
    public static function getSeparator(): string;
    public function filterTransfer(TransferInterface $transfer): array;
    public function filterArray(array $data): array;
    public function apply(): array;
    public function getInclude(): array;
    public function getExclude(): array;
    public function getProperties(): array;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public static function isInclude(string $rule): bool;
    public static function isExclude(string $rule): bool;
    public static function makeRaw(string $rule): string;
    public static function makeExclude(string $property): string;
}
