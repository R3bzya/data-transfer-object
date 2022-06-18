<?php

namespace Rbz\Data;

use Rbz\Data\Interfaces\Support\CollectionInterface;
use Rbz\Data\Interfaces\Errors\ErrorBagInterface;
use Rbz\Data\Interfaces\CompositeTransferInterface;
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
        parent::load(Arr::except($data, $this->internalTransfers()));
        $this->transferManager()->massiveLoad($data);
        return $this->isLoad();
    }
    
    public function validate(array $properties = [], bool $clearErrors = true): bool
    {
        $properties = new Properties($properties);
        parent::validate($properties->get(), $clearErrors);
        $this->transferManager()->massiveValidate($properties->get($this->internalTransfers()), $clearErrors);
        return $this->isValidate();
    }

    public function errors(): ErrorBagInterface
    {
        return parent::errors()->merge($this->transferManager()->getErrors());
    }

    public function getProperty(string $property)
    {
        if ($this->transferManager()->has($property)) {
            return $this->transferManager()->get($property);
        }
        return parent::getProperty($property);
    }

    public function setProperty(string $property, $value): void
    {
        if (Arr::in($this->internalTransfers(), $property, true)) {
            $this->transferManager()->add($property, $value);
        } elseif ($this->collector()->has($property)) {
            parent::setProperty($property, $this->collector()->toCollect($property, $value));
        } else {
            parent::setProperty($property, $value);
        }
    }

    public function isSetProperty(string $property): bool
    {
        return parent::isSetProperty($property) || $this->transferManager()->has($property);
    }

    public function getProperties(): CollectionInterface
    {
        return parent::getProperties()->with($this->internalTransfers());
    }
    
    public function isLoad(): bool
    {
        return parent::isLoad() || $this->transferManager()->isLoad();
    }
    
    public function isValidate(): bool
    {
        return parent::isValidate() && $this->transferManager()->isValidate();
    }
}
