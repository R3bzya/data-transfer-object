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

    /**
     * @param mixed $data
     * @return static
     */
    public function load($data);

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

    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    public function getItems(): array;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param $value
     * @param bool $strict
     * @return bool
     */
    public function in($value, bool $strict = false): bool;

    /**
     * @param $value
     * @param bool $strict
     * @return bool
     */
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
     * @param static $collection
     * @return static
     */
    public function merge($collection);

    /**
     * @param static $collection
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
    public function detach(string $key, $default = null);

    /**
     * @param mixed $data
     * @return static
     */
    public function diff($data);

    /**
     * @param int $offset
     * @param int|null $length
     * @param bool $preserveKeys
     * @return static
     */
    public function slice(int $offset = 0, int $length = null, bool $preserveKeys = false);
}
