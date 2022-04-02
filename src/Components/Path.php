<?php

namespace Rbz\Data\Components;

use ArrayIterator;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Collections\CollectionInterface;
use Rbz\Data\Interfaces\Components\Path\PathInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class Path implements PathInterface
{
    private string $path;

    public function __construct(string $path = '')
    {
        $this->path = $path;
    }

    /**
     * Make the new path instance.
     *
     * @param string|array $path
     * @return static
     * @throws PathException
     */
    public static function make($path = '')
    {
        switch (gettype($path)) {
            case 'string': return new static($path);
            case 'array': return new static(static::makeString($path));
        }
        throw new PathException('Path type must be array or string');
    }

    public static function separator(): string
    {
        return '.';
    }

    /**
     * Get the path separator.
     *
     * @return string
     */
    public static function getSeparator(): string
    {
        return static::separator();
    }

    /**
     * Get path as string.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->path;
    }

    /**
     * Determine if the path is nested.
     *
     * @return bool
     */
    public function isNested(): bool
    {
        return $this->count() > 1;
    }

    /**
     * Merge the path with the given path.
     *
     * @param static $path
     * @return static
     */
    public function merge($path)
    {
        $this->path = $this->get().static::separator().$path->get();
        return $this;
    }

    /**
     * Get the new path merged with the given path.
     *
     * @param static $path
     * @return static
     */
    public function with($path)
    {
        return $this->clone()->merge($path);
    }

    /**
     * Make string path from array.
     *
     * @param array $path
     * @return string
     */
    public static function makeString(array $path): string
    {
        return Arr::implode($path, static::separator());
    }

    /**
     * Make array from the string path.
     *
     * @param string $path
     * @return array
     */
    public static function makeArray(string $path): array
    {
        return Str::explode($path, static::separator());
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function toCollection(): CollectionInterface
    {
        return Arr::collect(static::makeArray($this->get()));
    }

    public function getIterator(): ArrayIterator
    {
        return Arr::getIterator($this->toArray());
    }

    public function count(): int
    {
        return $this->toCollection()->count();
    }

    /**
     * Determine if two paths is same.
     *
     * @param static $path
     * @return bool
     */
    public function is($path): bool
    {
        return $this->get() === $path->get();
    }

    /**
     * Determine if two paths are not the same.
     *
     * @param static $path
     * @return bool
     */
    public function isNot($path): bool
    {
        return ! $this->is($path);
    }

    /**
     * Get the first section of the path.
     *
     * @return static
     * @throws PathException
     */
    public function first()
    {
        return $this->slice(0, 1);
    }

    /**
     * Get the last section of the path.
     *
     * @return static
     * @throws PathException
     */
    public function last()
    {
        return $this->slice($this->count());
    }

    /**
     * Extract a slice of the path.
     *
     * @param int $offset
     * @param int|null $length
     * @return static
     * @throws PathException
     */
    public function slice(int $offset = 0, int $length = null)
    {
        return static::make($this->toCollection()->slice($offset, $length)->toArray());
    }

    public function clone()
    {
        return clone $this;
    }
}
