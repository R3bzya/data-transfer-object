<?php

namespace Rbz\Data\Interfaces;

use Countable;
use Rbz\Data\Interfaces\Support\Arrayable;

interface StorageInterface extends Arrayable, Countable
{
    /**
     * Add the value by key to the storage.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function add(string $key, $value): void;
    
    /**
     * Remove the value by key from the storage.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void;
    
    /**
     * Check if the key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
    
    /**
     * Get the value by key.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
    
    /**
     * Determine if the storage is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;
}