<?php

namespace Rbz\Data\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionProviderInterface;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;
use Rbz\Data\Interfaces\Components\Filter\FilterInterface;
use Rbz\Data\Interfaces\Components\Path\PathProviderInterface;

interface TransferInterface extends PropertiesInterface, PathProviderInterface, ErrorCollectionProviderInterface
{
    public static function make($data = []): TransferInterface;

    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $properties = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function getCollector(): CollectorInterface;
    public function getFilter(): FilterInterface;
    public function getClassName(): string;
    public function hasErrors(): bool;
    public function setFilter(FilterInterface $filter): void;
}
