<?php

namespace Rbz\Forms\Interfaces;

use Rbz\Forms\Collections\Validation\ExceptionItem;

/**
 * @deprecated
 */
interface ValidationAttributeCollectionInterface extends CollectionInterface
{
    public function add(string $rule): void;
    public function addItem(ExceptionItem $item): void;
    public function hasInclude(): bool;
    public function hasExclude(): bool;
    public function filter(array $attributes, bool $keys = false): array;
}
