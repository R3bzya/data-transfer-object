<?php

namespace Rbz\DataTransfer\Interfaces\Collections;

use Rbz\DataTransfer\Collections\Accessible\AccessibleItem;

interface AccessibleCollectionInterface extends CollectionInterface
{
    public function add(string $rule): void;
    public function addItem(AccessibleItem $item): void;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public function getIncludes(): array;
    public function getExcludes(): array;
    public function filterKeys(array $attributes): array;
    public function filter(array $attributes): array;
    public function with(AccessibleCollectionInterface $collection): AccessibleCollectionInterface;
    public function merge(AccessibleCollectionInterface $collection): AccessibleCollectionInterface;
}
