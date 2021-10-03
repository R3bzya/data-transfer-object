<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\TransferInterface;

interface FactoryInterface
{
    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface;
    public static function makeIsLoad(TransferInterface $transfer): ValidatorInterface;
    public static function makeIsSet(TransferInterface $transfer): ValidatorInterface;
    public static function makeIsNull(TransferInterface $transfer): ValidatorInterface;
}
