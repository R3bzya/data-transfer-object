<?php

namespace Rbz\Data\Interfaces\Components;

use Rbz\Data\Interfaces\Support\Arrayable;

interface StorageInterface extends Arrayable
{
    public function add(string $key, $value): void;
    
    public function remove(string $key): void;
    
    public function has(string $key): bool;
    
    public function get(string $key);
}