<?php

namespace Rbz\Data\Interfaces\Collections\Error;

interface CollectionProviderInterface
{
    public function setErrors(CollectionInterface $collection): void;

    public function errors(): CollectionInterface;

    public function getErrors(): CollectionInterface;

    public function hasErrors(): bool;
}
