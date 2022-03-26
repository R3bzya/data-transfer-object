<?php

namespace Rbz\Data\Interfaces\Validation;

use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionProviderInterface;

interface ValidatorInterface extends ErrorCollectionProviderInterface
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
