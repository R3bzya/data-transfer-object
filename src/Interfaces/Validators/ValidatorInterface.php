<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

interface ValidatorInterface
{
    public function setAccessible(AccessibleCollectionInterface $accessible): void;
    public function setErrors(ErrorCollectionInterface $errors): void;
    public function getAccessible(): AccessibleCollectionInterface;
    public function getErrors(): ErrorCollectionInterface;
    public function validate(array $attributes = []): bool;
    public function validateCustom(array $data, array $rules): bool;
    public function validateAttributes(TransferInterface $transfer, array $rules, array $attributes): bool;
    public function validateTransferIsLoad(TransferInterface $transfer, array $attributes): bool;
}
