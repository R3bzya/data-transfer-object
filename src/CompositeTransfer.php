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

    public function load($data): bool
    {
        $data = Collection::make($data)->toArray();
        $success = parent::load($data);
        foreach ($this->container()->toArray() as $property => $transfer) {
            $success = $transfer->load($this->getDataByTransfer($data, $property)) && $success;
        }
        return $success;
    }

    public function getDataByTransfer(array $data, string $key): array
    {
        if (isset($data[$key]) && is_array($data[$key])) {
            return $data[$key];
        }
        return $data;
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

    public function setProperty(string $property, $value): void
    {
        if ($this->container()->has($property)) {
            $this->container()->add($property, $value);
        } else {
            parent::setProperty($property, $value);
        }
    }

    public function getProperties(): CollectionInterface
    {
        return parent::getProperties()->with($this->container()->keys());
    }

    public function getProperty(string $property)
    {
        if ($this->container()->has($property)) {
            return $this->container()->get($property);
        }
        return parent::getProperty($property);
    }

    public function isTransferData(string $property, $data): bool
    {
        return $this->container()->has($property) && is_array($data);
    }

    public function errors(): ErrorCollectionInterface
    {
        $collection = parent::errors();
        foreach ($this->container()->toArray() as $transfer) {
            $collection->merge($transfer->getErrors());
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->errors()->isNotEmpty();
    }

    public function toCollection(): CollectionInterface
    {
        return parent::toCollection()->with($this->container()->toCollection());
    }

    public function toSafeCollection(): CollectionInterface
    {
        return parent::toSafeCollection()->with($this->container()->toCollection());
    }
}
