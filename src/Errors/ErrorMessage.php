<?php

namespace Rbz\Forms\Errors;

use DomainException;

/**
 * @method static required(string $property = '')
 * @method static notLoad(string $property = '')
 * @method static notSet(string $property = '')
 * @method static undefined(string $property = '')
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
