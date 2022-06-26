<?php

namespace Rbz\Data\Support;

use Closure;
use Rbz\Data\Interfaces\Support\Arrayable;
use RuntimeException;

/**
 * @method static bool array(mixed $value)
 * @method static bool notArray(mixed $value)
 * @method static bool string(mixed $value)
 * @method static bool notString(mixed $value)
 * @method static bool integer(mixed $value)
 * @method static bool notInteger(mixed $value)
 * @method static bool float(mixed $value)
 * @method static bool notFloat(mixed $value)
 * @method static bool numeric(mixed $value)
 * @method static bool notNumeric(mixed $value)
 * @method static bool object(mixed $value)
 * @method static bool notObject(mixed $value)
 * @method static bool callable(mixed $value)
 * @method static bool notCallable(mixed $value)
 * @method static bool bool(mixed $value)
 * @method static bool notBool(mixed $value)
 * @method static bool null(mixed $value)
 * @method static bool notNull(mixed $value)
 * @method static bool iterable(mixed $value)
 * @method static bool notIterable(mixed $value)
 * @method static bool scalar(mixed $value)
 * @method static bool notScalar(mixed $value)
 * @method static bool empty(mixed $value)
 * @method static bool notEmpty(mixed $value)
 */
class Is
{
    public static function __callStatic($name, $arguments)
    {
        $name = Str::toLower($name);
        if (Str::startWith($name, 'not')) {
            return static::checkTerms(Str::ltrim($name, 'not'), $arguments[0]);
        }
        return static::checkTerms($name, $arguments[0]);
    }
    
    /**
     * Determine if the value satisfies the condition.
     *
     * @param Closure|string $condition
     * @param mixed $value
     * @return bool
     */
    public static function check($condition, $value): bool
    {
        if ($condition instanceof Closure) {
            return $condition($value);
        }
        
        if (Str::isNegative($condition)) {
            return ! static::checkTerms(Str::toPositive($condition), $value);
        }
        return static::checkTerms($condition, $value);
    }
    
    protected static function checkTerms(string $condition, $value): bool
    {
        switch (static::normalize($condition)) {
            case 'array': return is_array($value);
            case 'string': return is_string($value);
            case 'integer': return is_integer($value);
            case 'float': return is_float($value);
            case 'numeric': return is_numeric($value);
            case 'object': return is_object($value);
            case 'callable': return is_callable($value);
            case 'bool': return is_bool($value);
            case 'null': return is_null($value);
            case 'iterable': return is_iterable($value);
            case 'scalar': return is_scalar($value);
            case 'empty': return static::isEmpty($value);
            default: throw new RuntimeException("Undefined condition: $condition");
        }
    }
    
    public static function normalize(string $condition): string
    {
        switch ($condition) {
            case 'arr': return 'array';
            case 'str': return 'string';
            case 'int': return 'integer';
            default: return $condition;
        }
    }
    
    protected static function isEmpty($value): bool
    {
        if ($value instanceof \Countable) {
            return $value->count() == 0;
        } elseif ($value instanceof Arrayable) {
            return Arr::isEmpty($value->toArray());
        }
    
        return empty($value);
    }
}