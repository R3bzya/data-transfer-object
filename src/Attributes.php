<?php

namespace Rbz\Forms;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

abstract class Attributes implements Arrayable
{
    public function setAttributes(array $data): bool
    {
        foreach ($data as $attribute => $value) {
            if ($this->hasAttribute($attribute)) {
                try {
                    $this->setAttribute($attribute, $value);
                } catch (Throwable $e) {
                    return false;
                }
            }
        }

        return true;
    }

    public function getAttributes(): array
    {
        $class = new ReflectionClass($this);

        $attributes = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if (! $property->isStatic()) {
                $attributes[] = $property->getName();
            }
        }

        return $attributes;
    }

    public function getAttribute(string $attribute)
    {
        return $this->$attribute;
    }

    public function setAttribute(string $attribute, $value): void
    {
        $this->$attribute = $value;
    }

    public function hasAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getAttributes());
    }

    public function toArray(): array
    {
        $attributes = [];
        foreach ($this->getAttributes() as $attribute) {
            $attributes[$attribute] = $this->getAttribute($attribute);
        }
        return $attributes;
    }

    public function isSetAttributes(): bool
    {
        foreach ($this->getAttributes() as $attribute) {
            if (! $this->isSetAttribute($attribute)) {
                return false;
            }
        }
        return true;
    }

    public function isSetAttribute(string $attribute): bool
    {
        return isset($this->$attribute);
    }

    public function isAvailableAttributes(): bool
    {
        foreach ($this->getAttributes() as $attribute) {
            if (! $this->isAvailableAttribute($attribute)) {
                return false;
            }
        }

        return true;
    }

    public function isAvailableAttribute(string $attribute): bool
    {
        return $this->isSetAttribute($attribute) || $this->isNullAttribute($attribute);
    }

    public function isNullAttribute(string $attribute): bool
    {
        try {
            return is_null($this->getAttribute($attribute));
        } catch (Throwable $e) {}

        return false;
    }
}
