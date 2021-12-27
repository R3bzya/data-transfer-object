<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Interfaces\Validators\ValidatorInterface;
use Rbz\Data\Validation\Validators\Laravel;

abstract class Validator implements ValidatorInterface
{
    /**
     * @param array $data
     * @param array $rules
     * @return Validator
     */
    public static function make(array $data, array $rules): Validator
    {
        return new Laravel($data, $rules);
    }

    public static function getDefaultRules(): array
    {
        return Laravel::defaultRules();
    }

    protected static function defaultRules(): array
    {
        return [];
    }
}
