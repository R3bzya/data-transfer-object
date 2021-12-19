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
        $collection = Collection::make($data);
        parent::load($collection->toArray());
        $this->container()->toCollection()->each(function (Transfer $transfer, string $property) use ($collection) {
            $transfer->load($collection->isArray($property) ? $collection->get($property) : $collection->toArray());
        });
        return $this->errors()->isEmpty();
    }

    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        parent::validate($properties, $clearErrors);
        $this->container()->toCollection()->each(fn(Transfer $transfer) => $transfer->validate($properties, $clearErrors));
        return $this->errors()->isEmpty();
    }

    public function errors(): ErrorCollectionInterface
    {
        $collection = parent::errors();
        $this->container()->toCollection()->each(fn(Transfer $transfer) => $collection->merge($transfer->getErrors()));
        return $collection;
    }

    public function getProperties(): CollectionInterface
    {
        return parent::getProperties()->with($this->container()->keys());
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
