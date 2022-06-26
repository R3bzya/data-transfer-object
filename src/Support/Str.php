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
        return Is::string($value);
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
        return static::pos($haystack, $needle) !== false;
    }
    
    public static function notContains(string $haystack, string $needle): bool
    {
        return ! static::contains($haystack, $needle);
    }

    public static function first(string $string, $default = null, string $separator = '.'): ?string
    {
        return Arr::first(static::explode($string, $separator), $default);
    }

    public static function concat(string $string1, string $string2, string $separator = ''): string
    {
        return $string1.$separator.$string2;
    }

    /**
     * @return int|false
     */
    public static function pos(string $haystack, string $needle)
    {
        return strpos($haystack, $needle);
    }
    
    /**
     * Determinate if the string start with exclamation point.
     *
     * @param string $string
     * @return bool
     */
    public static function isNegative(string $string): bool
    {
        return static::startWith($string, '!');
    }
    
    /**
     * Make the negative string if the string is positive.
     *
     * @param string $string
     * @return string
     */
    public static function toNegative(string $string): string
    {
        if (static::isNegative($string)) {
            return $string;
        }
        return '!'.$string;
    }
    
    /**
     * Make the positive string.
     *
     * @param string $string
     * @return string
     */
    public static function toPositive(string $string): string
    {
        return static::ltrim($string, '!');
    }
    
    public static function ltrim(string $string, string $characters = ' \t\n\r\0\x0B'): string
    {
        return ltrim($string, $characters);
    }
    
    public static function toLower(string $string): string
    {
        return strtolower($string);
    }
    
    /**
     * Determinate if the string is empty.
     *
     * @param string $string
     * @return bool
     */
    public static function isEmpty(string $string): bool
    {
        return trim($string) == '';
    }
    
    /**
     * Determinate if the string is not empty.
     *
     * @param string $string
     * @return bool
     */
    public function isNotEmpty(string $string): bool
    {
        return ! static::isEmpty($string);
    }
}
