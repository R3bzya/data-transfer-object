<?php

namespace Rbz\DataTransfer\Interfaces\Validators;

use Rbz\DataTransfer\Interfaces\TransferInterface;

interface FactoryInterface
{
    public static function make(TransferInterface $transfer, array $rules, array $attributes): ValidatorInterface;
    public static function makeIsLoad(TransferInterface $transfer, array $attributes): ValidatorInterface;
    public static function makeIsSet(TransferInterface $transfer, array $attributes): ValidatorInterface;
    public static function makeIsNull(TransferInterface $transfer, array $attributes): ValidatorInterface;
}
