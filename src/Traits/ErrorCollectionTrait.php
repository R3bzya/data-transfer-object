<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;

trait ErrorCollectionTrait
{
    private ErrorCollectionInterface $_errors;

    public function setErrors(ErrorCollectionInterface $collection): void
    {
        $this->_errors = $collection;
    }

    public function errors(): ErrorCollectionInterface
    {
        if (! isset($this->_errors)) {
            $this->_errors = ErrorCollection::make();
        }
        return $this->_errors;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->errors();
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }
}
