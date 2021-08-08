<?php

namespace Rbz\Forms\Interfaces;

use Rbz\Forms\Interfaces\Collections\AccessibleCollectionInterface;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;

interface ValidatorInterface
{
    public function setAccessible(AccessibleCollectionInterface $collection): void;
    public function setErrors(ErrorCollectionInterface $collection): void;
    public function getAccessible(): AccessibleCollectionInterface;
    public function getErrors(): ErrorCollectionInterface;
    public function validateAttributes(): bool;
    public function validateAttribute(string $attribute): bool;
    public function customValidate(array $rules): bool;
    public function loadAccessible(array $data): void;
}
