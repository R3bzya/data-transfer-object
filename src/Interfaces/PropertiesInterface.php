<?php

namespace Rbz\Data\Interfaces;

use Rbz\Data\Interfaces\Collections\CollectionInterface;

interface PropertiesInterface
{
    public function setProperties(array $data): void;
    public function setProperty(string $property, $value): void;
    public function getProperties(): CollectionInterface;
    public function getProperty(string $property);
    public function hasProperty(string $property): bool;
    public function isSetProperty(string $property): bool;
    public function isNullProperty(string $property): bool;
    public function isPublicProperty(string $property): bool;
}
