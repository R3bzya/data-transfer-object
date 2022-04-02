<?php

namespace Rbz\Data\Validation\Support;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Data
{
    public static function encode(array $data): array
    {
        $encoded = [];
        foreach ($data as $key => $value) {
            $encoded[Str::replace(['.', '*'], ['__dot__', '__asterisk__'], $key)] = Arr::is($value)
                ? self::encode($value)
                : $value;
        }
        return $encoded;
    }

    public static function decode(array $data): array
    {
        $decoded = [];
        foreach ($data as $key => $value) {
            $decoded[Str::replace(['__dot__', '__asterisk__'], ['.', '*'], $key)] = Arr::is($value)
                ? self::decode($value)
                : $value;
        }
        return $decoded;
    }
}
