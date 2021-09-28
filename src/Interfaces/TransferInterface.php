<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface TransferInterface extends AttributesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function setValidator(TransferValidatorInterface $validator): void;
    public function getValidator(): TransferValidatorInterface;
    public function getTransferName(): string;
}
