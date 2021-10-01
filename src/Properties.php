<?php

namespace Rbz\DataTransfer;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\PropertiesInterface;
use Rbz\DataTransfer\Traits\MagicPropertiesTrait;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

abstract class Properties implements PropertiesInterface
{
    use MagicPropertiesTrait;

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
