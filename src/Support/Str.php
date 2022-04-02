<?php

namespace Rbz\Data\Support;

class Str
{
    public static function startWith(?string $string, ?string $needle): bool
    {
        return str_starts_with($string, $needle);
    }

    public static function is($value): bool
    {
        return is_string($value);
    }

    public static function isNot($value): bool
    {
        return ! self::is($value);
    }

    public static function explode(string $path, string $separator): array
    {
        return explode($separator, $path);
    }

    /**
     * @param array|string $search
     * @param array|string $replace
     * @param array|string $subject
     * @param int|null $count
     * @return array|string|string[]
     */
    public static function replace($search, $replace, $subject, int &$count = null)
    {
        return str_replace($search, $replace, $subject, $count);
    }
}
