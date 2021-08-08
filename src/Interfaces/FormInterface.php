<?php

namespace Rbz\Forms\Interfaces;

use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;

interface FormInterface extends AttributesInterface
{
    public function load(array $data): bool;
    public function validate(array $attributes = []): bool;
    public function getErrors(): ErrorCollectionInterface;
    public function setValidator(ValidatorInterface $validator): void;
    public function getValidator(): ValidatorInterface;
}
