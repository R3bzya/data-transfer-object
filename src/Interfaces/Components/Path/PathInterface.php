<?php

namespace Rbz\Data\Interfaces\Components\Path;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface PathInterface extends Arrayable, IteratorAggregate, Countable
{
    /**
     * @param string|array $path
     * @return PathInterface
     */
    public static function make($path): PathInterface;

    public function get(): string;

    public function isInternal(): bool;

    public function with(PathInterface $path): PathInterface;

    public static function makeString(array $path): string;

    public static function makeArray(string $path): array;

    public static function getSeparator(): string;

    public function equalTo(PathInterface $path): bool;

    public function geFirstSection(): PathInterface;

    public function getLastSection(): PathInterface;

    /**
     * @param int $offset
     * @param int|null $length
     * @return PathInterface
     */
    public function slice(int $offset = 0, int $length = null): PathInterface;
}
