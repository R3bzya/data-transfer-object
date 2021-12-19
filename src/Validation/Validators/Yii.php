<?php

namespace Rbz\Data\Validation\Validators;

use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Validation\Validator as AbstractValidator;

class Yii extends AbstractValidator
{
    public function __construct(array $data, array $rules)
    {
    }

    public function validate(): bool
    {
        throw new \RuntimeException('Not implemented `yii` validator');
    }

    public function getErrors(): ErrorCollectionInterface
    {
        throw new \RuntimeException('Not implemented `yii` validator');
    }
}
