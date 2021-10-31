<?php

namespace Rbz\Data\Components;

use ArrayIterator;
use Rbz\Data\Interfaces\Components\Path\PathInterface;

class Path implements PathInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function make(string $path): PathInterface
    {
        return new self($path);
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

    public function last(): PathInterface
    {
        if (! $this->isInternal()) {
            return self::make($this->get());
        }
        return self::make($this->toArray()[$this->count() - 1]);
    }

    public function count(): int
    {
        return count(self::makeArray($this->get()));
    }

    public function equal(PathInterface $path): bool
    {
        return $this->get() === $path->get();
    }
}
