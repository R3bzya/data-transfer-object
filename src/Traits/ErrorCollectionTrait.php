<?php

namespace Rbz\DataTransfer\Traits;

use Rbz\DataTransfer\Collections\Error\ErrorCollection;
use Rbz\DataTransfer\Interfaces\Collections\ErrorCollectionInterface;

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
            $this->errorCollection = new ErrorCollection();
        }
        return $this->errorCollection;
    }

    public function getErrors(): ErrorCollectionInterface
    {
        return $this->errors();
    }
}
