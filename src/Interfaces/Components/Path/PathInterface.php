<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Countable;
use Rbz\Data\Interfaces\Support\Arrayable;
use IteratorAggregate;
use Rbz\Data\Interfaces\Support\Cloneable;
use Rbz\Data\Interfaces\Support\Collectable;

interface PathInterface extends Arrayable, Collectable, IteratorAggregate, Countable, Cloneable
{
    /**
     * Make the new path instance.
     *
     * @param string|array $path
     * @return static
     */
    public static function make($path);

    /**
     * Get path as string.
     *
     * @return string
     */
    public function get(): string;

    /**
     * Determine if the path is nested.
     *
     * @return bool
     */
    public function isNested(): bool;

    /**
     * Merge the path with the given path.
     *
     * @param static $path
     * @return static
     */
    public function merge($path);

    /**
     * Get the new path merged with the given path.
     *
     * @param static $path
     * @return static
     */
    public function with($path);

    /**
     * Make string path from array.
     *
     * @param array $path
     * @return string
     */
    public static function makeString(array $path): string;

    /**
     * Make array from the string path.
     *
     * @param string $path
     * @return array
     */
    public static function makeArray(string $path): array;

    /**
     * Get the path separator.
     *
     * @return string
     */
    public static function getSeparator(): string;

    /**
     * Determine if two paths is same.
     *
     * @param static $path
     * @return bool
     */
    public function is($path): bool;

    /**
     * Determine if two paths are not the same.
     *
     * @param static $path
     * @return bool
     */
    public function isNot($path): bool;

    /**
     * Get the first section of the path.
     *
     * @return static
     */
    public function first();

    /**
     * Get the last section of the path.
     *
     * @return static
     */
    public function last();

    /**
     * Extract a slice of the path.
     *
     * @param int $offset
     * @param int|null $length
     * @return static
     */
    public function slice(int $offset = 0, int $length = null);

    /**
     * Extract a slice of the path by string.
     *
     * @param string $offset
     * @param int|null $length
     * @return static
     */
    public function sliceBy(string $offset, int $length = null);
}
