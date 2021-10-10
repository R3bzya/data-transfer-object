<?php

namespace Rbz\DataTransfer\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;

interface CollectionInterface extends Arrayable
{
    public function load(array $data): void;
    public function has(string $property): bool;
    public function keys(): array;
    public function get(string $property);
    public function getItems(): array;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function count(): int;
    public function clear(): void;
}
