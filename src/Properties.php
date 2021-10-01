<?php

namespace Rbz\DataTransfer;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\PropertiesInterface;
use Rbz\DataTransfer\Traits\MagicPropertiesTrait;
use ReflectionClass;
use ReflectionProperty;

abstract class Properties implements PropertiesInterface
{
    use MagicPropertiesTrait;

    public function setProperties(array $data): void
    {
        foreach ($data as $property => $value) {
            if ($this->hasProperty($property)) {
                $this->setProperty($property, $value);
            }
        }
    }

    public function getProperties(): array
    {
        $reflection = new ReflectionClass($this);

        $properties = [];
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isStatic()) {
                $properties[] = $property->getName();
            }
        }

        return $properties;
    }

    public function getProperty(string $property)
    {
        return $this->$property;
    }

    public function setProperty(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function hasProperty(string $property): bool
    {
        return in_array($property, $this->getProperties());
    }

    public function toArray(): array
    {
        $properties = [];
        foreach ($this->getProperties() as $property) {
            $properties[$property] = $this->isArrayableProperty($property)
                ? $this->getProperty($property)->toArray()
                : $this->getProperty($property);
        }
        return $properties;
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

    public function isArrayableProperty(string $property): bool
    {
        return $this->getProperty($property) instanceof Arrayable;
    }
}
