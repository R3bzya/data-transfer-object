<?php

namespace Rbz\Data\Interfaces\Collections\Error;

interface ErrorCollectionProviderInterface
{
    /**
     * @param ErrorCollectionInterface $collection
     * @return static
     */
    public function setErrors(ErrorCollectionInterface $collection);

    public function errors(): ErrorCollectionInterface;

    public function getErrors(): ErrorCollectionInterface;

    public function hasErrors(): bool;
}
