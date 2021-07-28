<?php

namespace Rbz\Forms;

use Rbz\Forms\Errors\Collection\ErrorCollection;

abstract class FormErrors extends Attributes
{
    private ErrorCollection $errors;

    public function errors(): ErrorCollection
    {
        if (! isset($this->errors)) {
            $this->errors = new ErrorCollection();
        }
        return $this->errors;
    }

    public function getErrors(): ErrorCollection
    {
        return $this->errors();
    }

    public function setErrors(ErrorCollection $collection): void
    {
        $this->errors = $collection;
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }
}
