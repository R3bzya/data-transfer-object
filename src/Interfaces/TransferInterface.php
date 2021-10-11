<?php

namespace Rbz\DataTransfer\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Components\CombinatorInterface;

interface TransferInterface extends PropertiesInterface
{
    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $properties = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function getCombinator(): CombinatorInterface;
    public function getTransferName(): string;
    public function hasErrors(): bool;
}
