<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Events\EventManager;

trait EventsTrait
{
    private EventManager $eventManager;
    
    public function eventManager(): EventManager
    {
        if (! isset($this->eventManager)) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }
    
    public function addEvent(string $event, $callback): void
    {
        $this->eventManager()->addEvent($event, fn() => $callback($this));
    }
    
    public function releaseEvent(string $event): void
    {
        $this->eventManager()->release($event);
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addBeforeLoadEvent($callback)
    {
        $this->addEvent('beforeLoad', $callback);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addAfterLoadEvent($callback)
    {
        $this->addEvent('afterLoad', $callback);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addBeforeValidateEvent($callback)
    {
        $this->addEvent('beforeValidate', $callback);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addAfterValidateEvent($callback)
    {
        $this->addEvent('afterValidate', $callback);
        return $this;
    }
    
    public function beforeLoadEvents(): void
    {
        $this->releaseEvent('beforeLoad');
    }
    
    public function afterLoadEvents(): void
    {
        $this->releaseEvent('afterLoad');
    }
    
    public function beforeValidateEvents(): void
    {
        $this->releaseEvent('beforeValidate');
    }
    
    public function afterValidateEvents(): void
    {
        $this->releaseEvent('afterValidate');
    }
    
    /**
     * @return static
     */
    public function withoutEvents()
    {
        $this->eventManager()->disable();
        return $this;
    }
    
    /**
     * @return static
     */
    public function withEvents()
    {
        $this->eventManager()->enable();
        return $this;
    }
}