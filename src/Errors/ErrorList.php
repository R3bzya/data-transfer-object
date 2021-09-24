<?php

namespace Rbz\DataTransfer\Errors;

use DomainException;

class ErrorList
{
    const REQUIRED = 'required';

    const NOT_LOAD = 'notLoad';
    const NOT_SET = 'notSet';

    const UNDEFINED = 'undefined';

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
        if (key_exists($error, self::getListDescription())) {
            return str_replace( '  ', ' ', self::getListDescription($property)[$error]);
        }
        throw new DomainException('Error description not found');
    }
}
