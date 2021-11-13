<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\CompositeTransferInterface;
use Rbz\Data\Traits\ContainerTrait;

abstract class CompositeTransfer extends Transfer
    implements CompositeTransferInterface
{
    use ContainerTrait;

    abstract public function internalTransfers(): array;

    public function load($data): bool
    {
        $data = Collection::make($data)->toArray();
        $success = parent::load($data);
        foreach ($this->container()->toArray() as $property => $transfer) {
            $success = $transfer->load($this->getTransferData($property, $data)) && $success;
        }
        return $success;
    }

    public function getTransferData(string $transfer, array $data): array
    {
        if (! isset($data[$transfer]) || ! is_array($data[$transfer])) {
            return $data;
        }
        return $data[$transfer];
    }

    public function validate(array $properties = []): bool
    {
        $validate = parent::validate($properties);
        foreach ($this->container()->toArray() as $transfer) {
            $validate = $transfer->validate($properties) && $validate;
        }
        return $validate;
    }

    public function setProperties(array $data): void
    {
        $collection = Collection::make($data);
        parent::setProperties($collection->except($this->container()->keys()->toArray())->toArray());
        foreach ($collection->filter(fn($data, $property) => $this->isTransferData($property, $data)) as $transfer => $value) {
            $this->container()->get($transfer)->setProperties($value);
        }
    }

    public function getProperties(): CollectionInterface
    {
        return parent::getProperties()->with($this->container()->keys());
    }

    public function isTransferData(string $property, $data): bool
    {
        return $this->container()->has($property) && is_array($data);
    }

    public function getErrors(): ErrorCollectionInterface
    {
        $collection = parent::getErrors();
        foreach ($this->container()->toArray() as $transfer) {
            $collection->merge($transfer->getErrors());
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    public function toCollection(): CollectionInterface
    {
        return parent::toCollection()->with($this->container()->toCollection());
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }
}
