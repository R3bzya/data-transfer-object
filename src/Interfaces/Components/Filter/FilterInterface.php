<?php

namespace Rbz\Data\Interfaces\Components\Filter;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface FilterInterface extends Arrayable, PathProviderInterface
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
}
