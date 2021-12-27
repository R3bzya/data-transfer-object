<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Exceptions\PropertyException;
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

    public function setProperty(string $property, $value): void
    {
        if (! $this->hasProperty($property)) {
            throw new PropertyException('Setting private property:' . get_class($this) . '::' . $property);
        }
        $this->$property = $value;
    }

    public function getProperties(): CollectionInterface
    {
        return Collection::make($this->getReflectionInstance()->getProperties(ReflectionProperty::IS_PUBLIC))
            ->filter(fn(ReflectionProperty $property) => ! $property->isStatic())
            ->map(fn(ReflectionProperty $property) => $property->getName());
    }

    public function getProperty(string $property)
    {
        if (! $this->hasProperty($property)) {
            throw new PropertyException('Getting private property:' . get_class($this) . '::' . $property);
        }
        return $this->$property;
    }

    public function hasProperty(string $property): bool
    {
        return $this->getProperties()->in($property, true);
    }

    public function isSetProperty(string $property): bool
    {
        return isset($this->$property);
    }

    public function getReflectionInstance(): ReflectionClass
    {
        return new ReflectionClass($this);
    }
}
