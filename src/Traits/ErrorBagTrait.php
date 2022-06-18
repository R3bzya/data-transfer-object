<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Support\Errors\ErrorBag;
use Rbz\Data\Interfaces\Errors\ErrorBagInterface;

trait ErrorBagTrait
{
    private ErrorBagInterface $errors;

    public function setErrors(ErrorBagInterface $collection)
    {
        $this->errors = $collection;
        return $this;
    }

    public function errors(): ErrorBagInterface
     {
        if (! isset($this->errors)) {
            $this->errors = ErrorBag::make();
        }
        return $this->errors;
    }

    public function getErrors(): ErrorBagInterface
    {
        return $this->errors();
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }
}
