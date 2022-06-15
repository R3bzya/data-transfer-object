<?php

namespace Rbz\Data\Events;

use Closure;
use Rbz\Data\Interfaces\Events\StorageInterface;
use Rbz\Data\Support\Arr;
use Rbz\Data\Support\Str;

class EventStorage implements StorageInterface
{
    private array $groups = [];
    
    /**
     * @param string $group
     * @param Closure $closure
     * @return void
     */
    public function add(string $group, Closure $closure): void
    {
        $storage = $this;
        
        foreach (Str::explode($group) as $group) {
            if ($storage->has($group)) {
                $storage = $storage->get($group);
            } else {
                $storage = $storage->groups[$group] = new static();
            }
        }
        
        $storage->groups[] = $closure;
    }
    
    public function remove(string $group): void
    {
        $storage = $this;
        $groups = Str::explode($group);
        
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
    
    public function has(string $group): bool
    {
        $storage = $this;
        $groups = Str::explode($group);
        
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
    
    public function get(string $group): StorageInterface
    {
        $storage = $this;
        
        foreach (Str::explode($group) as $group) {
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