<?php

namespace Rbz\DataTransfer\Properties;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\PropertiesInterface;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

abstract class Properties extends Overload implements PropertiesInterface
{
    public function setProperties(array $data): bool
    {
        $success = true;
        foreach ($data as $property => $value) {
            if ($this->hasProperty($property)) {
                $success = $this->setProperty($property, $value) && $success;
            }
        }
        return $success;
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

    public function setProperty(string $property, $value): bool
    {
        try {
            $this->$property = $value;
            return true;
        } catch (Throwable $e) {}

        return false;
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
                ? $this->getPropertyAsArray($property)
                : $this->getProperty($property);
        }
        return $properties;
    }

    public function getPropertyAsArray(string $property): array
    {
        if ($this->isArrayableProperty($property)) {
            return $this->getProperty($property)->toArray();
        }
        throw new \DomainException('Property not arrayable: ' . get_class($this) . '::' . $property);
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
        try {
            return is_null($this->getProperty($property));
        } catch (Throwable $e) {}

        return false;
    }

    public function isArrayableProperty(string $property): bool
    {
        try {
            return $this->getProperty($property) instanceof Arrayable;
        } catch (Throwable $e) {}

        return false;
    }
}
