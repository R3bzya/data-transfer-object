<?php

namespace Rbz\Data\Events;

class EventManager extends EventStorage
{
    private bool $isEnable = true;
    
    private EventStorage $eventStorage;
    
    public function __construct()
    {
        $this->eventStorage = new EventStorage();
    }
    
    public function addEvent(string $event, $closure): void
    {
        $this->eventStorage->add($event, $closure);
    }
    
    public function release(string $group)
    {
        if (! $this->isEnable()) {
            return;
        }
        
        foreach ($this->eventStorage->get($group)->getEvents() as $event) {
            $event();
        }
    }
    
    public function disable()
    {
        $this->isEnable = false;
        return $this;
    }
    
    public function enable()
    {
        $this->isEnable = true;
        return $this;
    }
    
    public function isEnable(): bool
    {
        return $this->isEnable;
    }
}