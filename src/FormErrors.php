<?php

namespace Rbz\Forms;

use Rbz\Forms\Errors\Collection\ErrorCollection;
use Rbz\Forms\Errors\ErrorMessage;

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

    public function getFirstError(?string $attribute = null): ?string
    {
        return $this->errors()->getFirstMessage($attribute);
    }

    public function getErrorCount(): int
    {
        return $this->errors()->count();
    }

    public function setAttribute(string $attribute, $value): bool
    {
        if (! parent::setAttribute($attribute, $value)) {
            $this->errors()->add($attribute, ErrorMessage::notSet());
            return false;
        }
        return true;
    }
}
