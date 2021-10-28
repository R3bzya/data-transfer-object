<?php

namespace Rbz\Data\Interfaces\Collections;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use Rbz\Data\Interfaces\Components\Cloneable;

interface CollectionInterface extends Arrayable, IteratorAggregate, Countable, Cloneable
{
    /**
     * @param mixed $data
     * @return static
     */
    public static function make($data = []);
    public function getArrayFrom($value): array;
    public function load(array $data): void;
    public function add(string $key, $value = null): void;
    public function remove(string $key): void;
    public function get(string $key, $default = null);
    public function getItems(): array;
    public function has(string $key): bool;
    public function in($value): bool;

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

    /**
     * @param callable|null $callable
     * @return static
     */
    public function map(?callable $callable);

    /**
     * @return static
     */
    public function flip();

    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function keys(): CollectionInterface;
    public function clear(): void;
}
