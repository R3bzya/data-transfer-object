<?php

namespace Rbz\Forms\Interfaces\Collections;

use Rbz\Forms\Collections\Accessible\AccessibleItem;
use Rbz\Forms\Interfaces\FormInterface;

interface AccessibleCollectionInterface extends CollectionInterface
{
    public function add(string $rule): void;
    public function addItem(AccessibleItem $item): void;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public function filter(array $attributes, bool $keys = false): array;
    public function filterFormAttributes(FormInterface $form): array;
    public function isWaitValidation(string $attribute): bool;
}
