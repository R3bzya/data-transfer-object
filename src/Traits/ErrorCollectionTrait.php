<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Error\Collection;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;

trait ErrorCollectionTrait
{
    private CollectionInterface $errorCollection;

    public function setErrors(CollectionInterface $collection): void
    {
        $this->errorCollection = $collection;
    }

    public function errors(): CollectionInterface
    {
        if (! isset($this->errorCollection)) {
            $this->errorCollection = new Collection([]);
        }
        return $this->errorCollection;
    }

    public function getErrors(): CollectionInterface
    {
        return $this->errors();
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }
}
