<?php

namespace Rbz\Data\Interfaces\Collections\Error;

interface ErrorCollectionProviderInterface
{
    /**
     * Set the error collection to the instance.
     *
     * @param ErrorCollectionInterface $collection
     * @return static
     */
    public function setErrors(ErrorCollectionInterface $collection);

    /**
     * Get the error collection from the instance.
     *
     * @return ErrorCollectionInterface
     */
    public function getErrors(): ErrorCollectionInterface;

    /**
     * Determine if the instance has errors.
     *
     * @return bool
     */
    public function hasErrors(): bool;
}
