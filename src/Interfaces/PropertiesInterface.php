<?php

namespace Rbz\DataTransfer\Interfaces;

use Illuminate\Contracts\Support\Arrayable;

interface PropertiesInterface extends Arrayable
{
    public function setProperties(array $data): void;
    public function getProperties(): array;
    public function getProperty(string $property);
    public function setProperty(string $property, $value): void;
    public function hasProperty(string $property): bool;
    public function isSetProperties(): bool;
    public function isSetProperty(string $property): bool;
    public function isNullProperty(string $property): bool;
    public function isArrayableProperty(string $property): bool;
}
