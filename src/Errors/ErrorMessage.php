<?php

namespace Rbz\Forms\Errors;

use DomainException;

/**
 * @method static getIsNotSet(string $property = '')
 * @method static getNotLoad(string $property = '')
 */
class ErrorMessage
{
    /**
     * @throws DomainException
     */
    public static function __callStatic($name, $arguments)
    {
        $error = lcfirst(str_replace(['get'], '', $name));
        return ErrorList::getDescriptionByError($error, $arguments[0] ?? '');
    }
}
