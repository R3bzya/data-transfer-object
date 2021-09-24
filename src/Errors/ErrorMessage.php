<?php

namespace Rbz\DataTransfer\Errors;

use DomainException;

/**
 * @method static required(string $property = null)
 * @method static notLoad(string $property = null)
 * @method static notSet(string $property = null)
 * @method static undefined(string $property = null)
 */
class ErrorMessage
{
    /**
     * @throws DomainException
     */
    public static function __callStatic($name, $arguments)
    {
        return ErrorList::getDescriptionByError($name, $arguments[0] ?? '');
    }
}
