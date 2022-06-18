<?php

namespace Rbz\Data\Support;

class Str
{
    public static function startWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }
    
    /**
     * Determine if the value is string.
     *
     * @param mixed $value
     * @return bool
     */
    public static function is($value): bool
    {
        return is_string($value);
    }

    public static function isNot($value): bool
    {
        return ! static::is($value);
    }

    public static function explode(string $string, string $separator = '.'): array
    {
        return explode($separator, $string);
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

    public static function cmp(string $string1, string $string2, bool $strict = false): bool
    {
        return $strict ? $string1 === $string2 : $string1 == $string2;
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return str_contains($haystack, $needle);
    }

    public static function first(string $string, $default = null, string $separator = '.'): ?string
    {
        return Arr::first(static::explode($string, $separator), $default);
    }

    public static function concat(string $string1, string $string2, string $separator = ''): string
    {
        return $string1.$separator.$string2;
    }

    public static function has(string $haystack, string $needle): bool
    {
        return static::pos($haystack, $needle) !== false;
    }

    /**
     * @return int|false
     */
    public static function pos(string $haystack, string $needle)
    {
        return strpos($haystack, $needle);
    }

    public static function notContains(string $haystack, string $needle): bool
    {
        return ! static::contains($haystack, $needle);
    }
}
