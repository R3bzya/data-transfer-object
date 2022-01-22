<?php

namespace Rbz\Data;

use Rbz\Data\Collections\Collection;
use Rbz\Data\Exceptions\PropertyException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\PropertiesInterface;
use ReflectionClass;
use ReflectionProperty;

abstract class Properties implements PropertiesInterface
{
    /**
     * @param mixed $name
     * @return mixed
     * @throws PropertyException
     */
    public function __get($name)
    {
        return $this->getProperty($name);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return void
     * @throws PropertyException
     */
    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->isSetProperty($name);
    }

    /**
     * @param array $data
     * @return void
     * @throws PropertyException
     */
    public function setProperties(array $data): void
    {
        foreach ($data as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     * @throws PropertyException
     */
    public function setProperty(string $property, $value): void
    {
        if ($this->hasSetter($property)) {
            $this->setter($property, $value);
        } elseif ($this->hasProperty($property)) {
            $this->$property = $value;
        } elseif ($this->hasGetter($property)) {
            throw new PropertyException('Setting read-only property: ' . get_class($this) . '::' . $property);
        } else {
            throw new PropertyException('Setting unknown property: ' . get_class($this) . '::' . $property);
        }
    }

    /**
     * @return CollectionInterface
     */
    public function getProperties(): CollectionInterface
    {
        return Collection::make((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->filter(fn(ReflectionProperty $property) => ! $property->isStatic())
            ->map(fn(ReflectionProperty $property) => $property->getName());
    }

    /**
     * @param string $property
     * @return mixed
     * @throws PropertyException
     */
    public function getProperty(string $property)
    {
        if ($this->hasGetter($property)) {
            return $this->getter($property);
        } elseif ($this->hasProperty($property)) {
            return $this->$property;
        }
        throw new PropertyException('Getting unknown property: ' . get_class($this) . '::' . $property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        return $this->getProperties()->in($property, true) || $this->hasGetter($property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function isSetProperty(string $property): bool
    {
        if ($this->hasGetter($property)) {
            return $this->getter($property) !== null;
        }
        return isset($this->$property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasGetter(string $property): bool
    {
        return method_exists($this, "get{$property}Attribute");
    }

    /**
     * @param string $property
     * @return mixed
     */
    protected function getter(string $property)
    {
        return $this->{"get{$property}Attribute"}();
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasSetter(string $property): bool
    {
        return method_exists($this, "set{$property}Attribute");
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     */
    protected function setter(string $property, $value): void
    {
        $this->{"set{$property}Attribute"}($value);
    }
}
