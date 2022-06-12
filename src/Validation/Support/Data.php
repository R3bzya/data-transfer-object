<?php

namespace Rbz\Data\Validation\Support;

use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Data
{
    const DOT = '.';
    const ASTERISK = '*';
    const DOT_ENCODED = '__dot__';
    const ASTERISK_ENCODED = '__asterisk__';

    public static function encode(array $data): array
    {
        $encoded = [];
        foreach ($data as $key => $value) {
            $encoded[static::encodeKey($key)] = Arr::is($value) ? self::encode($value) : $value;
        }
        return $encoded;
    }

    public static function decode(array $data): array
    {
        $decoded = [];
        foreach ($data as $key => $value) {
            $decoded[static::decodeKey($key)] = Arr::is($value) ? self::decode($value) : $value;
        }
        return $decoded;
    }
    
    private static function encodeKey(string $key): string
    {
        return Str::replace([self::DOT, self::ASTERISK], [self::DOT_ENCODED, self::ASTERISK_ENCODED], $key);
    }
    
    private static function decodeKey(string $key): string
    {
        return Str::replace([self::DOT_ENCODED, self::ASTERISK_ENCODED], [self::DOT, self::ASTERISK], $key);
    }
}
