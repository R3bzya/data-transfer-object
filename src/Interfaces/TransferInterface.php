<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;

interface TransferInterface extends PropertiesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function setValidator(ValidatorInterface $validator): void;
    public function getValidator(): ValidatorInterface;
    public function getTransferName(): string;
}
