<?php

namespace Rbz\Data\Interfaces\Collections\Error\ValueObjects;

interface PathInterface
{
    public static function make(string $path): PathInterface;
    public function asString(): string;
    public function asArray(): array;
    public function isInternal(): bool;
    public function with(PathInterface $path): PathInterface;
    public static function toString(array $path): string;
    public static function toArray(string $path): array;
    public static function getSeparator(): string;
    public function withString(string $path, bool $separator = true): PathInterface;
}
