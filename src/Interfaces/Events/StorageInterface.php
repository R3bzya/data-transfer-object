<?php

namespace Rbz\Data\Interfaces\Events;

use Closure;
use Rbz\Data\Interfaces\Support\Arrayable;

interface StorageInterface extends Arrayable
{
    public function add(string $group, Closure $closure): void;
    
    public function remove(string $group): void;
    
    public function has(string $group): bool;
    
    public function get(string $group): StorageInterface;
    
    public function getEvents(): array;
}