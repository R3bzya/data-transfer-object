<?php

namespace Rbz\Data\Interfaces\Validators;

use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;

interface ValidatorInterface
{
    public function validate(): bool;
    public function getErrors(): ErrorCollectionInterface;
}
