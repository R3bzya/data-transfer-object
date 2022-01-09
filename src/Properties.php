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
        if ($this->hasGetter($name)) {
            return $this->getter($name);
        } elseif ($this->hasProperty($name)) {
            return $this->$name;
        }
        throw new PropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return void
     * @throws PropertyException
     */
    public function __set($name, $value)
    {
        if ($this->hasSetter($name)) {
            $this->setter($name, $value);
        } elseif ($this->hasProperty($name)) {
            $this->$name = $value;
        } elseif ($this->hasGetter($name)) {
            throw new PropertyException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new PropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function __isset($name)
    {
        if ($this->hasGetter($name)) {
            return $this->getter($name) !== null;
        }
        return isset($this->$name);
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
        $this->__set($property, $value);
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
        return $this->__get($property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        return property_exists($this, $property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function isSetProperty(string $property): bool
    {
        return $this->__isset($property);
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasGetter(string $property): bool
    {
        return method_exists($this, 'get' . $property);
    }

    /**
     * @param string $property
     * @return mixed
     */
    protected function getter(string $property)
    {
        $getter = 'get' . $property;
        return $this->$getter();
    }

    /**
     * @param string $property
     * @return bool
     */
    public function hasSetter(string $property): bool
    {
        return method_exists($this, 'set' . $property);
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     */
    protected function setter(string $property, $value): void
    {
        $setter = 'set' . $property;
        $this->$setter($value);
    }
}
