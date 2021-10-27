<?php

namespace Rbz\Data;

use DomainException;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface as ErrorCollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

abstract class CompositeTransfer extends Transfer
{
    public function load($data): bool
    {
        $data = Collection::make($data)->toArray();
        $success = parent::load($data);
        foreach ($this->getAdditionalTransfers() as $property => $transfer) {
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
        foreach ($this->getAdditionalTransfers() as $property => $transfer) {
            $validate = $transfer->setFilterPath(
                $this->filter()->hasPath()
                    ? $this->filter()->getPath()->with(Path::make($property))
                    : Path::make($property)
                )->validate($properties) && $validate;
        }
        return $validate;
    }

    public function setProperties(array $data): void
    {
        $collection = Collection::make($data);
        parent::setProperties($collection->except($this->getAdditionalTransfers()->keys()->toArray())->toArray());
        foreach ($collection->filter(fn($value, $key) => $this->isTransferData($key, $value)) as $transfer => $value) {
            $this->getTransfer($transfer)->load($value);
        }
    }

    public function isTransferData(string $property, $data): bool
    {
        return $this->isAdditionalTransfer($property) && is_array($data);
    }

    /**
     * @return CollectionInterface|TransferInterface[]
     */
    public function getAdditionalTransfers(): CollectionInterface
    {
        return Collection::make($this->getProperties())
            ->flip()
            ->filter(fn(string $key, string $property) => $this->isTransferAttribute($property))
            ->map(fn(string $key, string $property) => $this->getTransfer($property));
    }

    public function isTransferAttribute($attribute): bool
    {
        if ($this->isSetProperty($attribute)) {
            return $this->getProperty($attribute) instanceof TransferInterface;
        }
        return false;
    }

    public function isAdditionalTransfer(string $transfer): bool
    {
        return $this->getAdditionalTransfers()->keys()->has($transfer);
    }

    /**
     * @throws DomainException
     */
    public function getTransfer(string $attribute): TransferInterface
    {
        if (! $this->isTransferAttribute($attribute)) {
            throw new DomainException("Attribute `$attribute` is not implementing TransferInterface");
        }
        return $this->getProperty($attribute);
    }

    public function getErrors(): ErrorCollectionInterface
    {
        $collection = parent::getErrors();
        foreach ($this->getAdditionalTransfers() as $property => $transfer) {
            $collection->merge($transfer->getErrors()->withPath(Path::make($property)));
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }
}
