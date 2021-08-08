<?php

namespace Rbz\Forms\Interfaces;

use Illuminate\Contracts\Support\Arrayable;

interface AttributesInterface extends Arrayable
{
    public function setAttributes(array $data): bool;
    public function getAttributes(): array;
    public function getAttribute(string $attribute);
    public function setAttribute(string $attribute, $value): bool;
    public function hasAttribute(string $attribute): bool;
    public function isSetAttributes(): bool;
    public function isSetAttribute(string $attribute): bool;
    public function isNullAttribute(string $attribute): bool;
}
