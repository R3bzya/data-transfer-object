<?php

namespace Rbz\Forms\Validator\Rules;

use Rbz\Forms\Collections\Error\ErrorCollection;
use Rbz\Forms\Interfaces\Collections\ErrorCollectionInterface;
use Rbz\Forms\Interfaces\RuleInterface;

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
