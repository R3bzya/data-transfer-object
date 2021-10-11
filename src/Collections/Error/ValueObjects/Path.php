<?php

namespace Rbz\DataTransfer\Collections\Error\ValueObjects;

use Rbz\DataTransfer\Interfaces\Collections\Error\ValueObjects\PathInterface;

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

    public function asString(): string
    {
        return $this->path;
    }

    public function asArray(): array
    {
        return self::toArray($this->asString());
    }

    public function isInternal(): bool
    {
        return strpos($this->asString(), self::separator());
    }

    public function with(PathInterface $path): PathInterface
    {
        $clone = clone $this;
        $clone->path = $this->asString().self::separator().$path->asString();
        return $clone;
    }

    public static function toString(array $path): string
    {
        return implode(self::separator(), $path);
    }

    public static function toArray(string $path): array
    {
        return explode(self::separator(), $path);
    }

    public function withString(string $path, bool $separator = true): PathInterface
    {
        $clone = clone $this;
        $clone->path = $separator ? $clone->asString().self::separator().$path : $path;
        return $clone;
    }
}
