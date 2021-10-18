<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Collections\Error\ErrorCollection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

trait ErrorCollectionTrait
{
    private ErrorCollectionInterface $errorCollection;

    protected string $transferName;

    public function setErrors(ErrorCollectionInterface $collection): void
    {
        $this->errorCollection = $collection;
    }

    public function errors(): ErrorCollectionInterface
    {
        if (! isset($this->errorCollection)) {
            $this->errorCollection = (new ErrorCollection([]))->withPath(Path::make($this->getTransferName()));
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

    public function setTransferName(string $name): TransferInterface
    {
        $this->transferName = $name;
        return $this;
    }

    public function transferName(): string
    {
        return $this->transferName ?? get_class_name($this);
    }

    public function getTransferName(): string
    {
        return $this->transferName();
    }
}
