<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface TransferInterface extends PropertiesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function getTransferName(): string;
}
