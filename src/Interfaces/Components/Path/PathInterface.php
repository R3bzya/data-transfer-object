<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Countable;
use Rbz\Data\Interfaces\Arrayable;
use IteratorAggregate;

interface PathInterface extends Arrayable, IteratorAggregate, Countable
{
    /**
     * Make the new path instance.
     *
     * @param string|array $path
     * @return PathInterface
     */
    public static function make($path): PathInterface;

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
     * Get the new path merged with the given path.
     *
     * @param PathInterface $path
     * @return PathInterface
     */
    public function with(PathInterface $path): PathInterface;

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
     * Determine if the current path is equal to the given path.
     *
     * @param PathInterface $path
     * @return bool
     */
    public function equalTo(PathInterface $path): bool;

    /**
     * Get the first section of the path.
     *
     * @return PathInterface
     */
    public function firstSection(): PathInterface;

    /**
     * Get the last section of the path.
     *
     * @return PathInterface
     */
    public function lastSection(): PathInterface;

    /**
     * Extract a slice of the path.
     *
     * @param int $offset
     * @param int|null $length
     * @return PathInterface
     */
    public function slice(int $offset = 0, int $length = null): PathInterface;
}
