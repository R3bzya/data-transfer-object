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
    public function validateCustom(TransferInterface $transfer, array $rules): bool;
    public function validateAttributes(TransferInterface $transfer): bool;

    public function setCustomRules(array $rules): TransferValidatorInterface;
    public function customRules(): array;
    public function hasCustomRules(): bool;
}
