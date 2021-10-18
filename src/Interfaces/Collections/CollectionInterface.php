<?php

namespace Rbz\Data\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface CollectionInterface extends Arrayable, IteratorAggregate
{
    public function load(array $data): void;
    public function add(string $key, $value = null): void;
    public function remove(string $key): void;
    public function get(string $key, $default = null);
    public function getItems(): array;
    public function has(string $key): bool;
    public function only(array $keys): CollectionInterface;
    public function except(array $keys): CollectionInterface;
    public function count(): int;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function keys(): CollectionInterface;
    public function clear(): void;
    public function filterKeys(?callable $callable): CollectionInterface;
}
