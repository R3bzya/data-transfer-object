<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\PropertiesInterface;
use Rbz\Data\Traits\MagicPropertiesTrait;
use ReflectionClass;
use ReflectionProperty;

abstract class Properties implements PropertiesInterface
{
    use MagicPropertiesTrait;

    public function setProperties(array $data): void
    {
        foreach ($data as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->getReflectionInstance()->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isStatic()) {
                $properties[] = $property->getName();
            }
        }
        return $properties;
    }

    public function getProperty(string $property)
    {
        if (! $this->isPublicProperty($property)) {
            throw new \DomainException('Getting private property:' . get_class($this) . '::' . $property);
        }
        return $this->$property;
    }

    public function setProperty(string $property, $value): void
    {
        if (! $this->isPublicProperty($property)) {
            throw new \DomainException('Setting private property:' . get_class($this) . '::' . $property);
        }
        $this->$property = $value;
    }

    public function hasProperty(string $property): bool
    {
        return in_array($property, $this->getProperties(), true);
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function isSetProperties(): bool
    {
        foreach ($this->getProperties() as $property) {
            if (! $this->isSetProperty($property)) {
                return false;
            }
        }
        return true;
    }

    public function isSetProperty(string $property): bool
    {
        return isset($this->$property);
    }

    public function isNullProperty(string $property): bool
    {
        return is_null($this->getProperty($property));
    }

    public function getReflectionInstance(): ReflectionClass
    {
        return new ReflectionClass($this);
    }

    public function isPublicProperty(string $property): bool
    {
        if (! $this->getReflectionInstance()->hasProperty($property)) {
            return false;
        }
        return $this->getReflectionInstance()->getProperty($property)->isPublic();
    }

    public function toCollection(): CollectionInterface
    {
        $collection = Collection::make();
        foreach ($this->getProperties() as $property) {
            $collection->add($property, $this->getProperty($property));
        }
        return $collection;
    }
}
