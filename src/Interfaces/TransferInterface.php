<?php

namespace Rbz\Data\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionProviderInterface;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

interface TransferInterface extends PropertiesInterface, ErrorCollectionProviderInterface
{
    public static function make($data = []): TransferInterface;

    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $properties = []): bool;
    public function getCollector(): CollectorInterface;
    public function getShortClassName(): string;
}
