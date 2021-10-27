<?php

namespace Rbz\Data\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\Components\CollectorInterface;
use Rbz\Data\Interfaces\Components\FilterInterface;
use Rbz\Data\Interfaces\Components\PathInterface;

interface TransferInterface extends PropertiesInterface
{
    public static function make($data = []): TransferInterface;

    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $properties = []): bool;
    public function getErrors(): CollectionInterface;
    public function getCollector(): CollectorInterface;
    public function getFilter(): FilterInterface;
    public function getClassName(): string;
    public function hasErrors(): bool;
    public function setFilter(FilterInterface $filter): void;

    public function setFilterPath(PathInterface $path): TransferInterface;
}
