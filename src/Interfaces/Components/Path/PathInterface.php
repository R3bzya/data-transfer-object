<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface PathInterface extends Arrayable, IteratorAggregate
{
    public static function make(string $path): PathInterface;
    public function get(): string;
    public function isInternal(): bool;
    public function with(PathInterface $path): PathInterface;
    public static function makeString(array $path): string;
    public static function makeArray(string $path): array;
    public static function getSeparator(): string;
}
