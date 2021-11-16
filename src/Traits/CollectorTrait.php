<?php

namespace Rbz\Data\Traits;

use Rbz\Data\Components\Collector;
use Rbz\Data\Interfaces\Components\Collector\CollectorInterface;

trait CollectorTrait
{
    private CollectorInterface $_collector;

    public function setCollector(CollectorInterface $_collector): void
    {
        $this->_collector = $_collector;
    }

    public function collector(): CollectorInterface
    {
        if (! isset($this->_collector)) {
            $this->_collector = new Collector($this->collectable());
        }
        return $this->_collector;
    }

    public function getCollector(): CollectorInterface
    {
        return $this->collector();
    }

    public function collectable(): array
    {
        return [];
    }
}
