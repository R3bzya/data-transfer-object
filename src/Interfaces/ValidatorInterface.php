<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface ValidatorInterface
{
    public function setAccessible(AccessibleCollectionInterface $collection): void;
    public function setErrors(ErrorCollectionInterface $collection): void;
    public function getAccessible(): AccessibleCollectionInterface;
    public function getErrors(): ErrorCollectionInterface;

    public function customValidate(TransferInterface $transfer, array $rules): bool;
    public function validateTransfer(TransferInterface $transfer): bool;

    public function setAttributes(array $attributes): void;
}
