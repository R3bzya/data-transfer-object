<?php

namespace Rbz\Data\Interfaces\Errors;

interface ErrorBagProviderInterface
{
    /**
     * Set the error collection to the instance.
     *
     * @param ErrorBagInterface $collection
     * @return static
     */
    public function setErrors(ErrorBagInterface $collection);

    /**
     * Get the error collection from the instance.
     *
     * @return ErrorBagInterface
     */
    public function getErrors(): ErrorBagInterface;

    /**
     * Determine if the instance has errors.
     *
     * @return bool
     */
    public function hasErrors(): bool;
}
