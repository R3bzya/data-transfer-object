<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface TransferValidatorInterface
{
    public function setAccessible(AccessibleCollectionInterface $accessible): void;
    public function setErrors(ErrorCollectionInterface $errors): void;
    public function getAccessible(): AccessibleCollectionInterface;
    public function getErrors(): ErrorCollectionInterface;

    public function validate(array $attributes = []): bool;
    public function validateCustom(array $data, array $rules): bool;
    public function validateAttributes(TransferInterface $transfer, array $rules, array $attributes): bool;
    public function validateTransferLoad(TransferInterface $transfer, array $attributes): bool;
}
