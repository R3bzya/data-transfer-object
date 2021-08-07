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
        $success = true;
        foreach ($data as $attribute => $value) {
            if ($this->hasAttribute($attribute)) {
                $success = $this->setAttribute($attribute, $value) && $success;
            }
        }
        return $success;
    }

    public function getAttributes(): array
    {
        $reflection = new ReflectionClass($this);

        $attributes = [];
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
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

    public function setAttribute(string $attribute, $value): bool
    {
        try {
            $this->$attribute = $value;
            return true;
        } catch (Throwable $e) {}

        return false;
    }

    public function hasAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->getAttributes());
    }

    public function toArray(): array
    {
        $attributes = [];
        foreach ($this->getAttributes() as $attribute) {
            $attributes[$attribute] = $this->attributeAsArray($attribute);
        }
        return $attributes;
    }

    public function attributeAsArray(string $attribute)
    {
        return $this->isArrayableAttribute($attribute)
            ? $this->getAttribute($attribute)->toArray()
            : $this->getAttribute($attribute);
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

    public function isNullAttribute(string $attribute): bool
    {
        try {
            return is_null($this->getAttribute($attribute));
        } catch (Throwable $e) {}

        return false;
    }

    public function isArrayableAttribute(string $attribute): bool
    {
        try {
            return $this->getAttribute($attribute) instanceof Arrayable;
        } catch (Throwable $e) {}

        return false;
    }
}
