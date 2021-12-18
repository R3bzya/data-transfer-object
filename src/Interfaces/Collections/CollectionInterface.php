<?php

namespace Rbz\Data\Interfaces\Collections;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;
use Rbz\Data\Interfaces\Components\Cloneable;

interface CollectionInterface extends Arrayable, IteratorAggregate, Countable, Cloneable, ArrayAccess, TypableInterface
{
    /**
     * @param mixed $data
     * @return static
     */
    public static function make($data = []);

    public function load(array $data): void;

    /**
     * @param $value
     * @return static
     */
    public function add($value);

    /**
     * @param string $key
     * @param $value
     * @return static
     */
    public function set(string $key, $value = null);

    public function remove(string $key): void;
    public function get(string $key, $default = null);
    public function getItems(): array;
    public function has(string $key): bool;
    public function in($value, bool $strict = false): bool;
    public function notIn($value, bool $strict = false): bool;

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
     * @param callable $callable
     * @return static
     */
    public function each(callable $callable);

    /**
     * @return static
     */
    public function flip();

    /**
     * @param CollectionInterface $collection
     * @return static
     */
    public function merge($collection);

    /**
     * @param CollectionInterface $collection
     * @return static
     */
    public function with($collection);

    /**
     * @param mixed $data
     * @return static
     */
    public function replace($data);

    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function keys(): CollectionInterface;
    public function clear(): void;

    /**
     * @param string $key
     * @param mixed $default
     * @return static
     */
    public function collect(string $key, $default = []);

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function detach(string $key, $default = []);
}
