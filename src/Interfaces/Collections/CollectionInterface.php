<?php

namespace Rbz\Data\Interfaces\Collections;

use ArrayAccess;
use Countable;
use Rbz\Data\Interfaces\Arrayable;
use IteratorAggregate;
use Rbz\Data\Interfaces\Cloneable;

interface CollectionInterface extends Arrayable, IteratorAggregate, Countable, Cloneable,
    ArrayAccess, TypeCheckerInterface
{
    /**
     * Make the new instance of collection.
     *
     * @param mixed $data
     * @return static
     */
    public static function make($data = []);

    /**
     * Load the data to collection.
     *
     * @param mixed $data
     * @return static
     */
    public function load($data);

    /**
     * Add the item to collection.
     *
     * @param mixed $value
     * @return static
     */
    public function add($value);

    /**
     * Set the item to collection by key.
     *
     * @param mixed $key
     * @param mixed $value
     * @return static
     */
    public function set($key, $value = null);

    /**
     * Remove the value from collection by key.
     *
     * @param mixed $key
     * @return static
     */
    public function remove($key);

    /**
     * Get the value from collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Get all items from collection.
     *
     * @return array
     */
    public function getItems(): array;

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Determine if a value exists in the collector.
     *
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function in($value, bool $strict = false): bool;

    /**
     * Determine if a value not exists in the collector.
     *
     * @param mixed $value
     * @param bool $strict
     * @return bool
     */
    public function notIn($value, bool $strict = false): bool;

    /**
     * Get values from the collection by keys.
     *
     * @param array $keys
     * @return static
     */
    public function only(array $keys);

    /**
     * Get all non-excluded by keys values from the collection.
     *
     * @param array $keys
     * @return static
     */
    public function except(array $keys);

    /**
     * Run a filter over each of the items.
     *
     * @param callable|null $callable
     * @return static
     */
    public function filter(?callable $callable);

    /**
     * Run a map over each of the items.
     *
     * @param callable|null $callable
     * @return static
     */
    public function map(?callable $callable);

    /**
     * Run an associative map over each of the items.
     *
     * @param callable $callable
     * @return static
     */
    public function mapWithKeys(callable $callable);

    /**
     * Execute a callback over each item.
     *
     * @param callable $callable
     * @return static
     */
    public function each(callable $callable);

    /**
     * Flip the collection.
     *
     * @return static
     */
    public function flip();

    /**
     * Merge the collection with the given collection.
     *
     * @param static $collection
     * @return static
     */
    public function merge($collection);

    /**
     * Get the new collection merged with the given collection.
     *
     * @param static $collection
     * @return static
     */
    public function with($collection);

    /**
     * Replace the collection items with the given items.
     *
     * @param mixed $data
     * @return static
     */
    public function replace($data);

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool;

    /**
     * Get keys of the collection.
     *
     * @return CollectionInterface
     */
    public function keys(): CollectionInterface;

    /**
     * Clear the collection.
     *
     * @return static
     */
    public function clear();

    /**
     * Collect the value from the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return static
     */
    public function collect($key, $default = []);

    /**
     * Get and remove the item from the collection by key.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public function detach($key, $default = null);

    /**
     * Computes the difference of the value.
     *
     * @param mixed $data
     * @return static
     */
    public function diff($data);

    /**
     * Extract a slice items from the collection.
     *
     * @param int $offset
     * @param int|null $length
     * @param bool $preserveKeys
     * @return static
     */
    public function slice(int $offset = 0, int $length = null, bool $preserveKeys = false);

    /**
     * Get the first item from the collection.
     *
     * @return mixed
     */
    public function first($default = null);
}
