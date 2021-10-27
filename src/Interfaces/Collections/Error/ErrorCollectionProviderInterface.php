<?php

namespace Rbz\Data\Interfaces\Collections\Error;

interface ErrorCollectionProviderInterface
{
    public function setErrors(ErrorCollectionInterface $collection): void;

    public function errors(): ErrorCollectionInterface;

    public function getErrors(): ErrorCollectionInterface;

    public function hasErrors(): bool;
}
