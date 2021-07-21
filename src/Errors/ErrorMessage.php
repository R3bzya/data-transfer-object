<?php

namespace Rbz\Forms\Errors;

use DomainException;

/**
 * @method static getMessageIsNotSet(string $property = '')
 * @method static getMessageNotLoad(string $property = '')
 */
class ErrorMessage
{
    /**
     * @throws DomainException
     */
    public static function __callStatic($name, $arguments)
    {
        $error = lcfirst(str_replace(['getMessage'], '', $name));
        return ErrorList::getDescriptionByError($error, $arguments[0] ?? '');
    }
}
