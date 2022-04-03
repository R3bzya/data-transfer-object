<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Errors\ErrorBag;
use Rbz\Data\Interfaces\Errors\ErrorBagInterface;

trait ErrorBagTrait
{
    private ErrorBagInterface $_errors;

    public function setErrors(ErrorBagInterface $collection)
    {
        $this->_errors = $collection;
        return $this;
    }

    public function errors(): ErrorBagInterface
    {
        if (! isset($this->_errors)) {
            $this->_errors = ErrorBag::make();
        }
        return $this->_errors;
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
