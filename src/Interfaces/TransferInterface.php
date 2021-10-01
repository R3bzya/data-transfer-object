<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\Validators\FacadeInterface;

interface TransferInterface extends PropertiesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function setValidator(FacadeInterface $validator): void;
    public function getValidator(): FacadeInterface;
    public function getTransferName(): string;
}
