<?php

namespace Rbz\Data\Validation;

use Rbz\Data\Exceptions\ValidatorException;
use Rbz\Data\Interfaces\Validators\ValidatorInterface;
use Rbz\Data\Validation\Validators\Laravel;
use Rbz\Data\Validation\Validators\Symfony;
use Rbz\Data\Validation\Validators\Yii2;

abstract class Validator implements ValidatorInterface
{
    const LARAVEL = 'laravel';
    const YII_2 = 'yii_2';
    const SYMFONY = 'symfony';

    /**
     * @param array $data
     * @param array $rules
     * @return Validator
     * @throws ValidatorException
     */
    public static function make(array $data, array $rules): Validator
    {
        switch (self::LARAVEL) {
            case self::LARAVEL: return new Laravel($data, $rules);
            case self::YII_2: return new Yii2($data, $rules);
            case self::SYMFONY: return new Symfony($data, $rules);
        }
        throw new ValidatorException('Validator is not implemented');
    }
}
