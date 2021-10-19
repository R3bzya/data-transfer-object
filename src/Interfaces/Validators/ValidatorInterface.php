<?php

namespace Rbz\Data\Interfaces\Validators;

use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface ValidatorInterface
{
    public static function makeIsLoad(TransferInterface $transfer, array $properties): ValidatorInterface;
    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface;
    public function validate(): bool;
    public function getErrors(): CollectionInterface;
}
