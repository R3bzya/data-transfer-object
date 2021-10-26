<?php

namespace Rbz\Data;

use DomainException;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Components\Path;
use Rbz\Data\Interfaces\Collections\Error\CollectionInterface;
use Rbz\Data\Interfaces\TransferInterface;

abstract class CompositeTransfer extends Transfer
{
    /**
     * @throws DomainException
     */
    public function load($data): bool
    {
        $data = Collection::make($data)->toArray();
        $success = parent::load($data);
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $success = $this->getTransfer($transfer)->load($data[$transfer] ?? $data) && $success;
        }
        return $success;
    }

    public function validate(array $properties = []): bool
    {
        $validate = parent::validate($properties);
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $validate = $this->getTransfer($transfer)->validate($properties) && $validate;
        }
        return $validate;
    }

    public function setProperties(array $data): void
    {
        $collection = Collection::make($data);
        parent::setProperties($collection->except($this->getAdditionalTransfers())->toArray());
        foreach ($collection->filter(fn($value, $key) => $this->isTransferData($key, $value)) as $transfer => $value) {
            $this->getTransfer($transfer)->load($value);
        }
    }

    public function isTransferData(string $property, $data): bool
    {
        return $this->isAdditionalTransfer($property) && is_array($data);
    }

    public function getAdditionalTransfers(): array
    {
        $additionalTransfers = [];
        foreach ($this->getProperties() as $attribute) {
            if ($this->isTransferAttribute($attribute)) {
                $additionalTransfers[] = $attribute;
            }
        }
        return $additionalTransfers;
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
        return in_array($transfer, $this->getAdditionalTransfers());
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

    public function getErrors(): CollectionInterface
    {
        $collection = parent::getErrors();
        foreach ($this->getAdditionalTransfers() as $transfer) {
            $collection->merge($this->getTransfer($transfer)->getErrors()->withPath(Path::make($transfer)));
        }
        return $collection;
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }
}
