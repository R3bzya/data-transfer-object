<?php

namespace Rbz\Data\Interfaces\Validators;

use \Illuminate\Contracts\Validation\Validator as CustomValidatorInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

interface ValidatorInterface
{
    public static function makeIsLoad(TransferInterface $transfer, array $properties): ValidatorInterface;
    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface;
    public static function makeCustom(array $data, array $rules): CustomValidatorInterface;
    public function validate(): bool;
    public function getErrors(): ErrorCollectionInterface;
}
