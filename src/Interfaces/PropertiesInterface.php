<?php

namespace Rbz\Data\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Components\Collectable;

interface PropertiesInterface extends Arrayable, Collectable
{
    public function setProperties(array $data): void;
    public function getProperties(): CollectionInterface;
    public function getProperty(string $property);
    public function setProperty(string $property, $value): void;
    public function hasProperty(string $property): bool;
    public function isSetProperties(): bool;
    public function isSetProperty(string $property): bool;
    public function isNullProperty(string $property): bool;
    public function isPublicProperty(string $property): bool;
    public function getOnly(array $properties): CollectionInterface;
    public function getExcept(array $properties): CollectionInterface;
}
