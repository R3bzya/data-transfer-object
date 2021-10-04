<?php

namespace Rbz\DataTransfer\Validators;

use Rbz\DataTransfer\Interfaces\TransferInterface;
use Rbz\DataTransfer\Interfaces\Validators\ValidatorInterface;

/** ToDo не уверен в этом классе */
class Factory
{
    /**
     * @param TransferInterface $transfer
     * @param array $properties
     * @return ValidatorInterface
     */
    public static function makeIsLoad(TransferInterface $transfer, array $properties): ValidatorInterface
    {
        return self::make($transfer, self::addRulesToProperties($properties, ['has', 'isset']));
    }

    public static function make(TransferInterface $transfer, array $rules): ValidatorInterface
    {
        return new Validator($transfer, $rules);
    }

    public static function addRulesToProperties(array $properties, array $rules): array
    {
        $prepared = [];
        foreach ($properties as $property) {
            $prepared[$property] = $rules;
        }
        return $prepared;
    }
}
