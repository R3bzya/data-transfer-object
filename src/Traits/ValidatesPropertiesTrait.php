<?php

namespace Rbz\Data\Traits;

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
        return is_string($value);
    }

    public function validateArray(string $property, $value): bool
    {
        return is_array($value);
    }

    public function validatePresent(string $property, $value): bool
    {
        return ! empty($value);
    }
}
