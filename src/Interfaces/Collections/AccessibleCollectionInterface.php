<?php

namespace Rbz\Forms\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Forms\Collections\Accessible\AccessibleItem;
use Rbz\Forms\Interfaces\FormInterface;

interface AccessibleCollectionInterface extends Arrayable
{
    public function add(string $rule): void;
    public function addItem(AccessibleItem $item): void;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public function filter(array $attributes, bool $keys = false): array;
    public function filterFormAttributes(FormInterface $form): array;
    public function isWaitValidation(string $attribute): bool;

    public function load(array $data): void;
    public function with(AccessibleCollectionInterface $collection): AccessibleCollectionInterface;
    public function has(string $attribute): bool;
    public function get(string $attribute);
    public function getItems(): array;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function count(): int;
}
