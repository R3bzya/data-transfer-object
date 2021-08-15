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

    public function customValidate(FormInterface $form, array $rules, array $attributes = []): bool;
    public function validateForm(FormInterface $form, array $attributes = []): bool;
}
