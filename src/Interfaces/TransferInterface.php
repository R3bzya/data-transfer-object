<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Validators\ValidatorFactory;

interface TransferInterface extends PropertiesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function setValidator(ValidatorFactory $validator): void;
    public function getValidator(): ValidatorFactory;
    public function getTransferName(): string;
}
