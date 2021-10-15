<?php

namespace Rbz\Data\Interfaces\Components;

use Illuminate\Contracts\Support\Arrayable;
use IteratorAggregate;

interface DataInterface extends Arrayable, IteratorAggregate
{
    /**
     * @param array|Arrayable $data
     */
    public static function make($data = []): DataInterface;
    public static function accessible($data): bool;
    public function add(string $key, $value = null): void;
    public function set(string $key, $value = null): void;
    public function remove(string $key): void;
    public function all(): array;
    public function get(string $key, $default = null);
    public function getByPath(array $data, PathInterface $path,  $default);
    public function has(string $key): bool;
    public function only(array $keys): DataInterface;
    public function except(array $keys): DataInterface;
    public function replace(array $data): DataInterface;
    public function count(): int;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function keys(): DataInterface;
}
