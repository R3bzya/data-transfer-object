<?php

namespace Rbz\Forms\Interfaces\Collections;

use Rbz\Forms\Collections\Accessible\AccessibleItem;

interface AccessibleCollectionInterface extends CollectionInterface
{
    public function add(string $rule): void;
    public function addItem(AccessibleItem $item): void;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public function filter(array $attributes, bool $keys = false): array;
    public function isWaitValidation(string $attribute): bool;
}
