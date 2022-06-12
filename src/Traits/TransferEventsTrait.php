<?php

namespace Rbz\Data\Traits;

trait TransferEventsTrait
{
    protected array $afterLoad = [];
    protected array $beforeLoad = [];
    
    protected array $afterValidate = [];
    protected array $beforeValidate = [];
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addBeforeLoadEvent($callback)
    {
        $this->beforeLoad[] = fn() => $callback($this);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addAfterLoadEvent($callback)
    {
        $this->afterLoad[] = fn() => $callback($this);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addBeforeValidateEvent($callback)
    {
        $this->beforeValidate[] = fn() => $callback($this);
        return $this;
    }
    
    /**
     * @param callable|string $callback
     * @return static
     */
    public function addAfterValidateEvent($callback)
    {
        $this->afterValidate[] = fn() => $callback($this);
        return $this;
    }
    
    public function beforeLoadEvents(): void
    {
        foreach ($this->beforeLoad as $event) {
            $event();
        }
    }
    
    public function afterLoadEvents(): void
    {
        foreach ($this->afterLoad as $event) {
            $event();
        }
    }
    
    public function beforeValidateEvents(): void
    {
        foreach ($this->beforeValidate as $event) {
            $event();
        }
    }
    
    public function afterValidateEvents(): void
    {
        foreach ($this->afterValidate as $event) {
            $event();
        }
    }
}