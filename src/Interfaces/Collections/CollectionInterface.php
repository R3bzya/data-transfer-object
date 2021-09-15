<?php

namespace Rbz\Forms\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @deprecated
 */
interface CollectionInterface extends Arrayable
{
    public function load(array $data): void;
    public function with(CollectionInterface $collection): CollectionInterface;
    public function has(string $attribute): bool;
    public function get(string $attribute);
    public function getItems(): array;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function count(): int;
}
