<?php

namespace Rbz\Data\Validation\Support;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Messenger
{
    public static function getMessage(string $property, string $rule): string
    {
        return Str::replace(':property', $property, static::getTemplate($rule));
    }

    protected static function getTemplate(string $rule): string
    {
        return Arr::get(static::getTemplates(), $rule, "The :property did not pass the $rule");
    }

    protected static function getTemplates(): array
    {
        return [
            'integer' => 'The :property must be an integer.',
            'numeric' => 'The :property must be a numeric.',
            'string' => 'The :property must be a string.',
            'array' => 'The :property must be an array.',
            'present' => 'The :property must be a present.',
            'required' => 'The :property is required',
            'bool' => 'The :property must be a bool.',
        ];
    }
}
