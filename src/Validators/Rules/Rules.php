<?php

namespace Rbz\DataTransfer\Validators\Rules;

use Rbz\DataTransfer\Interfaces\RulesInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\ValidatorInterface;
use Rbz\DataTransfer\Validators\Rules\Validators\Validator;

class Rules implements RulesInterface
{
    public static function make(TransferInterface $transfer, array $rules, array $attributes): ValidatorInterface
    {
        return new Validator($transfer, $rules, $attributes);
    }

    public static function loaded(TransferInterface $transfer, array $attributes): ValidatorInterface
    {
        return self::make($transfer, ['has', 'isset'], $attributes);
    }
}
