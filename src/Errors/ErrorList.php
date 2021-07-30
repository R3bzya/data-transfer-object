<?php

namespace Rbz\Forms\Errors;

use DomainException;

class ErrorList
{
    const IS_NOT_SET = 'isNotSet';

    const NOT_LOAD = 'notLoad';
    const NOT_SET = 'notSet';

    const UNDEFINED_PROPERTY = 'undefinedProperty';

    public static function getListDescription(string $property = ''): array
    {
        return [
            self::IS_NOT_SET => sprintf('Поле %s обязательно для заполнения.', $property),
            self::NOT_LOAD => sprintf('Форма %s не заполнена.', $property),
            self::NOT_SET => sprintf('Поле %s не было заполнено.', $property),
            self::UNDEFINED_PROPERTY => sprintf('Поле %s неопределено.', $property),
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
