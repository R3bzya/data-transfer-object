<?php

namespace Rbz\DataTransfer\Interfaces;

use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

interface ValidatorInterface
{
    public function validate(): bool;
    public function getErrors(): ErrorCollectionInterface;
}
