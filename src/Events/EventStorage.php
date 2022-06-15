<?php

namespace Rbz\Data\Events;

use Closure;
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
        $storage = $this;
        $groups = Str::explode($key);
        
        foreach ($groups as $key => $group) {
            if (Arr::countEq($groups, 1)) {
                unset($storage->groups[$group]);
                break;
            }
            
            if ($storage->has($group)) {
                $storage = $storage->get($group);
            } else {
                break;
            }
            
            unset($groups[$key]);
        }
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $storage = $this;
        $groups = Str::explode($key);
        
        if (Arr::countEq($groups, 0)) {
            return false;
        }
        
        foreach ($groups as $group) {
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
            if ($this->has($group)) {
                $storage = $storage->groups[$group];
            } else {
                return new static();
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
}