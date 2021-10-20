<?php

namespace Rbz\Data\Errors;

use DomainException;
use ReflectionClass;

/**
 * @method static string required(string $property = null)
 * @method static string notLoad(string $property = null)
 * @method static string notSet(string $property = null)
 * @method static string undefined(string $property = null)
 */
class ErrorList
{
    const REQUIRED = 'required';
    const NOT_LOAD = 'notLoad';
    const NOT_SET = 'notSet';
    const UNDEFINED = 'undefined';

    /**
     * @throws DomainException
     */
    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, self::getList())) {
            return self::getDescriptionByError($name, $arguments[0] ?? '');
        }
        throw new DomainException("Call to undefined method: " . self::class . '::' . $name . "()");
    }

    public static function getList(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    public static function getListDescription(string $property = ''): array
    {
        return [
            self::REQUIRED => sprintf('Поле %s обязательно для заполнения.', $property),
            self::NOT_LOAD => sprintf('Форма %s не заполнена.', $property),
            self::NOT_SET => sprintf('Поле %s не было заполнено.', $property),
            self::UNDEFINED => sprintf('Поле %s неопределено.', $property),
        ];
    }

    /**
     * @param string $error
     * @param string $property
     * @return string
     * @throws DomainException
     */
    public static function getDescriptionByError(string $error, string $property = ''): string
    {
        if (! key_exists($error, self::getListDescription())) {
            throw new DomainException('Error description not found');
        }
        return str_replace( '  ', ' ', self::getListDescription($property)[$error]);
    }
}
