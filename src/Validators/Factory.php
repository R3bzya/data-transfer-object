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
     * @return ValidatorInterface
     */
    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface
    {
        return new Validator($transfer, $rules);
    }

    /**
     * @param TransferInterface $transfer
     * @return ValidatorInterface
     */
    public static function makeIsLoad(TransferInterface $transfer): ValidatorInterface
    {
        return self::make($transfer, [HasRule::class, IsSetRule::class]);
    }

    /**
     * @param TransferInterface $transfer
     * @return ValidatorInterface
     */
    public static function makeIsSet(TransferInterface $transfer): ValidatorInterface
    {
        return self::make($transfer, [IsSetRule::class]);
    }

    /**
     * @param TransferInterface $transfer
     * @return ValidatorInterface
     */
    public static function makeIsNull(TransferInterface $transfer): ValidatorInterface
    {
        return self::make($transfer, [IsNullRule::class]);
    }
}
