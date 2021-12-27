<?php

namespace Rbz\Data\Interfaces\Validators;

use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;

interface ValidatorInterface
{
    /**
     * Validate the data of the transfer.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Collect errors of the transfer.
     *
     * @return ErrorCollectionInterface
     */
    public function getErrors(): ErrorCollectionInterface;

    /**
     * Get default rules of the validation.
     *
     * @return array
     */
    public static function getDefaultRules(): array;
}
