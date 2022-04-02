<?php

namespace Rbz\Data;

use Rbz\Data\Components\Path;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Collections\Error\ErrorCollectionInterface;
use Rbz\Data\Interfaces\CompositeTransferInterface;
use Rbz\Data\Interfaces\TransferInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Traits\CollectorTrait;
use Rbz\Data\Traits\ContainerTrait;
use Rbz\Data\Support\Transfer\Properties;

abstract class CompositeTransfer extends Transfer
    implements CompositeTransferInterface
{
    use ContainerTrait,
        CollectorTrait;

    public function load($data): bool
    {
        $collection = Arr::collect($data);
        $this->container()->toCollection()->each(function (TransferInterface $transfer, string $property) use ($collection) {
            $transfer->load($collection->isArray($property) ? $collection->detach($property) : $collection->toArray());
        });
        parent::load($collection->toArray());
        return $this->errors()->isEmpty();
    }

    /**
     * @throws PathException
     */
    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $properties = new Properties($properties);
        parent::validate($properties->get(), $clearErrors);
        $this->container()->toCollection()->each(function (TransferInterface $transfer, $property) use ($properties, $clearErrors) {
            $transfer->validate($properties->get($property), $clearErrors);
        });
        return $this->errors()->isEmpty();
    }

    public function errors(): ErrorCollectionInterface
    {
        $collection = parent::errors();
        $this->container()->toCollection()->each(function (TransferInterface $transfer, string $property) use ($collection) {
            $collection->merge($transfer->getErrors()->withPathAtTheBeginning(Path::make($property)));
        });
        return $collection;
    }

    public function getProperty(string $property)
    {
        if ($this->container()->has($property)) {
            return $this->container()->get($property);
        }
        return parent::getProperty($property);
    }

    public function setProperty(string $property, $value): void
    {
        if (Arr::in($this->internalTransfers(), $property, true)) {
            $this->container()->add($property, $value);
        } elseif ($this->collector()->has($property)) {
            parent::setProperty($property, $this->collector()->toCollect($property, $value));
        } else {
            parent::setProperty($property, $value);
        }
    }

    public function isSetProperty(string $property): bool
    {
        return $this->container()->has($property) || parent::isSetProperty($property);
    }

    public function getProperties(): CollectionInterface
    {
        return parent::getProperties()->with($this->container()->keys());
    }
}
