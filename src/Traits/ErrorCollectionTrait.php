<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;

trait ErrorCollectionTrait
{
    private ErrorCollectionInterface $errorCollection;

    public function setErrors(ErrorCollectionInterface $collection): void
    {
        $this->errorCollection = $collection;
    }

    public function errors(): ErrorCollectionInterface
    {
        if (! isset($this->errorCollection)) {
            $this->errorCollection = ErrorCollection::make();
        }
        return $this->errorCollection;
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
