<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

trait ValidatesPropertiesTrait
{
    public function validateInteger(string $property, $value): bool
    {
        return is_integer($value);
    }

    public function validateNumeric(string $property, $value): bool
    {
        return is_numeric($value);
    }

    public function validateString(string $property, $value): bool
    {
        return Str::is($value);
    }

    public function validateArray(string $property, $value): bool
    {
        return Arr::is($value);
    }

    public function validatePresent(string $property, $value): bool
    {
        return ! empty($value);
    }
}