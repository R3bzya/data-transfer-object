<?php

namespace Rbz\Data\Interfaces\Validation;

use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Errors\ErrorBagProviderInterface;

interface ValidatorInterface extends ErrorBagProviderInterface
{
    public static function make(array $data, array $rules): ValidatorInterface;

    /**
     * Validate the data of the transfer.
     *
     * @return bool
     */
    public function validate(): bool;

    public function validated(): CollectionInterface;
}
