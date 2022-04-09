<?php

namespace Rbz\Data\Support;

use ArrayIterator;
use Rbz\Data\Interfaces\Support\Arrayable;
use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Support\Jsonable;

class Arr
{
    public static function dot(array $array, string $path = ''): array
    {
        $dotted = [];
        foreach ($array as $key => $value) {
            if (Arr::is($value) && ! empty($value)) {
                $dotted = Arr::merge($dotted, self::dot($value, $path.$key.'.'));
            } else {
                Arr::set($dotted, $path.$key, $value);
            }
        }
        return $dotted;
    }

    public static function first(array $array, $default = null)
    {
        foreach ($array as $value) {
            return $value;
        }
        return $default;
    }

    public static function get(array $array, $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    public static function set(array &$array, $key, $value): void
    {
        $array[$key] = $value;
    }

    public static function add(array &$array, $value): void
    {
        $array[] = $value;
    }

    /**
     * @param array $array
     * @param int|string $key
     * @return bool
     */
    public static function has(array $array, $key): bool
    {
        return key_exists($key, $array);
    }

    public static function is($value): bool
    {
        return is_array($value);
    }

    public static function isNot($value): bool
    {
        return ! self::is($value);
    }

    public static function clear(array &$items): void
    {
        $items = [];
    }

    public static function unset(array &$items, $key): void
    {
        unset($items[$key]);
    }

    public static function unique(array $param, int $flag = SORT_STRING): array
    {
        return array_unique($param, $flag);
    }

    public static function map(?callable $callable, array $array): array
    {
        return array_map($callable, $array);
    }

    public static function collect(array $array): CollectionInterface
    {
        return Collection::make($array);
    }

    /**
     * @param array|null $path
     * @param array|string $separator
     * @return string
     */
    public static function implode(array $path = null, $separator = ''): string
    {
        return implode($separator, $path);
    }

    public static function in(array $haystack, $needle, bool $strict = false): bool
    {
        return in_array($needle, $haystack, $strict);
    }

    public static function notIn(array $haystack, $needle, bool $strict = false): bool
    {
        return ! self::in($haystack, $needle, $strict);
    }

    public static function merge(array $array1, array $array2): array
    {
        return array_merge($array1, $array2);
    }

    public static function keys(array $array): array
    {
        return array_keys($array);
    }

    public static function flip(array $array): array
    {
        return array_flip($array);
    }

    public static function filter(array $array, ?callable $callable, int $mode = 0): array
    {
        return array_filter($array, $callable, $mode);
    }

    /**
     * @param array $keys
     * @param array $values
     * @return array|false
     */
    public static function combine(array $keys, array $values)
    {
        return array_combine($keys, $values);
    }

    public static function diff(array $array, array $array2): array
    {
        return array_diff($array, $array2);
    }

    public static function slice(array $array, int $offset = 0, int $length = null, bool $preserveKeys = false): array
    {
        return array_slice($array, $offset, $length, $preserveKeys);
    }

    public static function sliceBy(array $array, string $offset, ?int $length = null, bool $preserveKeys = false, $strict = false): array
    {
        return static::slice($array, Arr::search($array, $offset, $strict) + 1, $length, $preserveKeys); // TODO над +1 нужно подумать, там может вылететь false или string
    }

    public static function getIterator(array $array): ArrayIterator
    {
        return new ArrayIterator($array);
    }

    public static function make($value): array
    {
        if (self::is($value)) {
            return $value;
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        } elseif ($value instanceof Jsonable) {
            return json_decode($value->toJson());
        }
        return (array) $value;
    }

    public static function count(array $array): int
    {
        return count($array);
    }

    public static function countEq(array $array, int $int): bool
    {
        return self::count($array) == $int;
    }

    public static function countNe(array $array, int $int): bool
    {
        return self::count($array) != $int;
    }

    public static function countGt(array $array, int $int): bool
    {
        return self::count($array) > $int;
    }

    public static function countGte(array $array, int $int): bool
    {
        return self::count($array) >= $int;
    }

    public static function countLt(array $array, int $int): bool
    {
        return self::count($array) < $int;
    }

    public static function countLte(array $array, int $int): bool
    {
        return self::count($array) <= $int;
    }

    public static function isEmpty(array $array): bool
    {
        return self::countEq($array, 0);
    }

    public static function isNotEmpty(array $array): bool
    {
        return ! self::isEmpty($array);
    }

    /**
     * @param array $array
     * @param string $search
     * @param bool $strict
     * @return false|int|string
     */
    public static function search(array $array, string $search, bool $strict = false)
    {
        return array_search($search, $array, $strict);
    }
}
