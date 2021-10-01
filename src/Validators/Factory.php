<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\Validators\FactoryInterface;
use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;
use Rbz\DataTransfer\Validators\Rules\Attribute\HasRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsNullRule;
use Rbz\DataTransfer\Validators\Rules\Attribute\IsSetRule;

class Factory implements FactoryInterface
{
    /**
     * @param TransferInterface $transfer
     * @param array $rules
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function make(TransferInterface $transfer, array $rules, array $attributes): ValidatorInterface
    {
        return new Validator($transfer, $rules, $attributes);
    }

    /**
     * @param TransferInterface $transfer
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function makeIsLoad(TransferInterface $transfer, array $attributes): ValidatorInterface
    {
        return self::make($transfer, [HasRule::class, IsSetRule::class], $attributes);
    }

    /**
     * @param TransferInterface $transfer
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function makeIsSet(TransferInterface $transfer, array $attributes): ValidatorInterface
    {
        return self::make($transfer, [IsSetRule::class], $attributes);
    }

    /**
     * @param TransferInterface $transfer
     * @param array $attributes
     * @return ValidatorInterface
     */
    public static function makeIsNull(TransferInterface $transfer, array $attributes): ValidatorInterface
    {
        return self::make($transfer, [IsNullRule::class], $attributes);
    }
}
