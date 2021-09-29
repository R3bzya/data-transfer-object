<?php

namespace Rbz\DataTransfer\Validators\Rules;

use Rbz\DataTransfer\Interfaces\RulesInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\ValidatorInterface;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;
use Rbz\DataTransfer\Validators\Rules\Validators\AttributeValidator;

class Rules implements RulesInterface
{
    /**
     * @param TransferInterface $transfer
     * @param array $rules
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function make(TransferInterface $transfer, array $rules, array $attributes): ValidatorInterface
    {
        return new AttributeValidator($transfer, $rules, $attributes);
    }

    /**
     * @param TransferInterface $transfer
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function load(TransferInterface $transfer, array $attributes): ValidatorInterface
    {
        return self::make($transfer, [HasRule::class, IsSetRule::class], $attributes);
    }
}
