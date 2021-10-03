<?php

namespace Rbz\DataTransfer\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface TransferInterface extends PropertiesInterface
{
    /**
     * @param array|Arrayable $data
     * @return bool
     */
    public function load($data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function getTransferName(): string;
}
