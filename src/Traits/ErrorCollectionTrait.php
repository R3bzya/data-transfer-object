<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Error\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

trait ErrorCollectionTrait
{
    private CollectionInterface $errorCollection;

    protected string $transferName;

    public function setErrors(CollectionInterface $collection): void
    {
        $this->errorCollection = $collection;
    }

    public function errors(): CollectionInterface
    {
        if (! isset($this->errorCollection)) {
            $this->errorCollection = (new Collection([]))->withPath(Path::make($this->getClassName()));
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

    public function setTransferName(string $name): TransferInterface
    {
        $this->transferName = $name;
        return $this;
    }

    public function className(): string
    {
        return $this->transferName ?? get_class_name($this);
    }

    public function getClassName(): string
    {
        return $this->className();
    }
}
