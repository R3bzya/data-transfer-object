<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface PathInterface extends Arrayable, IteratorAggregate
{
    public static function make(string $path): PathInterface;
    public function asString(): string;
    public function asArray(): array;
    public function isInternal(): bool;
    public function with(PathInterface $path): PathInterface;
    public static function makeString(array $path): string;
    public static function makeArray(string $path): array;
    public static function getSeparator(): string;
    public function withString(string $path, bool $separator = true): PathInterface;
    public function next(): PathInterface;
}