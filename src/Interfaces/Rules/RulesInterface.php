<?php

namespace Rbz\DataTransfer\Interfaces\Rules;

use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\AttributeValidatorInterface;

interface RulesInterface
{
    public static function make(TransferInterface $transfer, array $rules, array $attributes): AttributeValidatorInterface;
    public static function load(TransferInterface $transfer, array $attributes): AttributeValidatorInterface;
}
