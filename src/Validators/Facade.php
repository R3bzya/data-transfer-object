<?php

namespace Rbz\Data\Validators;

use Rbz\Data\Interfaces\Validators\ValidatorInterface;

class Facade
{
    public static function make(array $data, array $rules): ValidatorInterface
    {
        throw new \RuntimeException('Not implemented ' . static::class);
    }
}
