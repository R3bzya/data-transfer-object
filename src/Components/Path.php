<?php

namespace Rbz\Data\Components;

use ArrayIterator;
use Rbz\Data\Interfaces\Components\PathInterface;

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
        return strpos($this->get(), self::separator());
    }

    public function with(PathInterface $path): PathInterface
    {
        $clone = clone $this;
        $clone->path = $this->get().self::separator().$path->get();
        return $clone;
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
        return explode(self::separator(), $this->get());
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }
}
