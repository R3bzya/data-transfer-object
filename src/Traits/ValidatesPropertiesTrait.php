<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

trait ValidatesPropertiesTrait
{
    public function validateRule(string $rule, string $property, $value): bool
    {
        $method = "validate{$rule}";
        return $this->$method($property, $value);
    }

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

    public function validateBool(string $property, $value): bool
    {
        return is_bool($value);
    }

    public function validateRequired(string $property, $value): bool
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif (is_array($value) && count($value) < 1) {
            return false;
        }

        return true;
    }
}
