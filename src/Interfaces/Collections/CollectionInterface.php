<?php

namespace Rbz\DataTransfer\Interfaces\Collections;

use Illuminate\Contracts\Support\Arrayable;
use Rbz\DataTransfer\Interfaces\Collections\Error\ErrorItemInterface;
use Rbz\DataTransfer\Interfaces\Collections\Error\ValueObjects\PathInterface;

interface CollectionInterface extends Arrayable
{
    public function load(array $data): void;
    public function has(string $property): bool;
    public function keys(): array;
    public function get(string $property);

    /**
     * @return ErrorItemInterface[]
     */
    public function getItems(): array;
    public function getPath(): PathInterface;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function count(): int;
    public function clear(): void;
}
