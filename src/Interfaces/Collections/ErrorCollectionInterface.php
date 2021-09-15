<?php

namespace Rbz\Forms\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\Forms\Collections\Error\ErrorItem;

interface ErrorCollectionInterface extends Arrayable
{
    public function add(string $attribute, string $message): void;
    public function addItem(ErrorItem $item): void;
    public function getFirstMessage(?string $attribute = null): ?string;
    public function getFirst(string $attribute);

    public function load(array $data): void;
    public function with(ErrorCollectionInterface $collection): ErrorCollectionInterface;
    public function has(string $attribute): bool;
    public function get(string $attribute);
    public function getItems(): array;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function count(): int;
}
