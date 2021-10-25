<?php

namespace Rbz\Data\Interfaces\Collections;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface CollectionInterface extends Arrayable, IteratorAggregate, Countable
{
    /**
     * @param mixed $data
     * @return static
     */
    public static function make($data = []);
    public static function getArrayFrom($value): array;
    public function load(array $data): void;
    public function add(string $key, $value = null): void;
    public function remove(string $key): void;
    public function get(string $key, $default = null);
    public function getItems(): array;
    public function has(string $key): bool;

    /**
     * @param array $keys
     * @return static
     */
    public function only(array $keys);

    /**
     * @param array $keys
     * @return static
     */
    public function except(array $keys);

    /**
     * @param callable|null $callable
     * @return static
     */
    public function filter(?callable $callable);

    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function keys(): CollectionInterface;
    public function clear(): void;
}
