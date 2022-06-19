<?php

namespace Rbz\Data\Events;

use Closure;
use Rbz\Data\Exceptions\EventStorageException;
use Rbz\Data\Interfaces\Events\StorageInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class EventStorage implements StorageInterface
{
    /** @var static[]|Closure[]|array  */
    private array $groups = [];
    
    /**
     * @param string $key
     * @param Closure $value
     * @return void
     */
    public function add(string $key, $value): void
    {
        $storage = $this;
        
        foreach (Str::explode($key) as $group) {
            if ($storage->has($group)) {
                $storage = $storage->get($group);
            } else {
                $storage = $storage->groups[$group] = new static();
            }
        }
        
        $storage->groups[] = $value;
    }
    
    /**
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        if (! $this->has($key)) {
            return;
        }
        
        $storage = $this;
        $groups = Str::explode($key);
        $key = Arr::pop($groups);
        
        foreach ($groups as $group) {
            $storage = $storage->get($group);
        }
    
        unset($storage->groups[$key]);
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if (Str::isEmpty($key)) {
            return false;
        }
        
        $storage = $this;
        
        foreach (Str::explode($key) as $group) {
            if (Arr::notHas($storage->groups, $group)) {
                return false;
            }
            $storage = $storage->groups[$group];
        }
        
        return true;
    }
    
    /**
     * @param string $key
     * @return static
     */
    public function get(string $key)
    {
        $storage = $this;
        
        foreach (Str::explode($key) as $group) {
            if ($storage->has($group)) {
                $storage = $storage->groups[$group];
            } else {
                throw new EventStorageException("The key not found: $key");
            }
        }
        
        return $storage;
    }
    
    public function getEvents(): array
    {
        $events = [];
        
        foreach ($this->groups as $event) {
            if ($event instanceof StorageInterface) {
                $events = Arr::merge($events, $event->getEvents());
            } else {
                $events[] = $event;
            }
        }
        
        return $events;
    }
    
    public function toArray(): array
    {
        return $this->groups;
    }
    
    public function count(): int
    {
        return Arr::count($this->groups);
    }
    
    public function isEmpty(): bool
    {
        return Arr::isEmpty($this->groups);
    }
}