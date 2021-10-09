<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;

interface ValidatorInterface
{
    public static function makeIsLoad(TransferInterface $transfer, array $properties): ValidatorInterface;
    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface;
    public function validate(): bool;
    public function getErrors(): ErrorCollectionInterface;
}
