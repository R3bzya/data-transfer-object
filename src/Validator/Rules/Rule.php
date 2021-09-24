<?php

namespace Rbz\DataTransfer\Validator\Rules;

use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\DataTransfer\Interfaces\RuleInterface;

abstract class Rule implements RuleInterface
{
    private ErrorCollectionInterface $errors;

    public function error(): ErrorCollectionInterface
    {
        if (! isset($this->errors)) {
            $this->errors = new ErrorCollection();
        }
        return $this->errors;
    }
}
