<?php

namespace Rbz\Data\Components;

use ArrayIterator;
use Rbz\Data\Collections\Collection;
use Rbz\Data\Exceptions\PathException;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

class Path implements PathInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function make($path): PathInterface
    {
        switch (gettype($path)) {
            case 'string': return new self($path);
            case 'array': return new self(self::makeString($path));
        }
        throw new PathException('Path type must be array or string');
    }

    public static function separator(): string
    {
        return '.';
    }

    public static function getSeparator(): string
    {
        return self::separator();
    }

    public function get(): string
    {
        return $this->path;
    }

    public function isInternal(): bool
    {
        return $this->count() > 1;
    }

    public function with(PathInterface $path): PathInterface
    {
        return new static($this->get().self::separator().$path->get());
    }

    public static function makeString(array $path): string
    {
        return implode(self::separator(), $path);
    }

    public static function makeArray(string $path): array
    {
        return explode(self::separator(), $path);
    }

    public function toArray(): array
    {
        return self::makeArray($this->get());
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }

    public function count(): int
    {
        return count($this->toArray());
    }

    public function equalTo(PathInterface $path): bool
    {
        return $this->get() === $path->get();
    }

    public function lastSection(): PathInterface
    {
        return $this->slice($this->count());
    }

    public function firstSection(): PathInterface
    {
        return $this->slice(0, 1);
    }

    public function slice(int $offset = 0, int $length = null): PathInterface
    {
        return static::make(Collection::make($this->toArray())->slice($offset, $length)->toArray());
    }
}
